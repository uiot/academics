#include "LAYERS_ExecutionLayer.h"


ExecutionLayer EL = ExecutionLayer();



//*****************************************************************************************************************//
void ExecutionLayer::check_debug(){
#if DEBUG
	Serial.println(F("Debug mode is on in Execution Layer."));
#endif
}
ExecutionLayer::ExecutionLayer(){
	//setup intervals for reading poins
	previousMillis = 0;

	//setup initial pins values
#if ARDUINO_UNO
	unsigned char readable_digital_pins  [] = {0,1,2,3,4,5,6,7,8,9,10,11,12,13};
	unsigned char readable_analog_pins [] = {0,1,2,3,4,5};
	int d = 14;
#if DEBUG_CHECK_ANALOG_PINS
			int a = 6;
#else
			int a = 0;
#endif
#endif
#if ARDUINO_MEGA
	unsigned char readable_digital_pins [] = {/*0,1,*/2,3,4,5,6,7,8,9,12,13,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53};
	unsigned char readable_analog_pins [] = {};//{0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15};
	int d = 42;
#if DEBUG_CHECK_ANALOG_PINS
			int a = 16;
#else
			int a = 0;
#endif
#endif

	//Setup initial values of digital pins

	digital_pin_state = new Interface[d];
	for(register unsigned char i = 0; i < d; i++){
		digital_pin_state[i].value = 0;
		digital_pin_state[i].number = (int)readable_digital_pins[i];
	}

	//Setup initial values of analog pins

	analog_pin_state = new Interface[a];
	for(register unsigned char j = 0; j < a; j++){
		analog_pin_state[j].number = (int)readable_analog_pins[j];
		analog_pin_state[j].value = 0;
	}
}



//*****************************************************************************************************************//
void ExecutionLayer :: monitor(){
	unsigned long currentMillis = millis();
	if(currentMillis - previousMillis > RECHECK_PIN_STATE_INTERVAL) {
		previousMillis = currentMillis;
#if ARDUINO_UNO
		int d = 14;
#if DEBUG_CHECK_ANALOG_PINS
			int a = 6;
#else
			int a = 0;
#endif
#endif
#if ARDUINO_MEGA
		int d = 44;
#if DEBUG_CHECK_ANALOG_PINS
			int a = 16;
#else
			int a = 0;
#endif
#endif
		//Checks for digital pins

		for(register unsigned char i = 0; i < d; i++){
			int value = digitalRead(digital_pin_state[i].number);
			if(value != digital_pin_state[i].value){
				digital_pin_state[i].value = value;
				CL.notify_new_value(digital_pin_state[i].number,digital_pin_state[i].value,-1);
			}else{
#if DEBUG_VERBOSE_EL
				Serial.print(F("D("));
				Serial.print(digital_pin_state[i].number);
				Serial.print(F(","));
				Serial.print(digital_pin_state[i].value);
				Serial.print(F(","));
				Serial.print(value);
				Serial.print(F(") "));
#endif
			}
		}

		//Checks for analog pins
		for(register unsigned char i = 0; i < a; i++){
			int value = analogRead((int)analog_pin_state[i].number);
			if(value != analog_pin_state[i].value){
				analog_pin_state[i].value = value;
				CL.notify_new_value(analog_pin_state[i].number,analog_pin_state[i].value,-1);
			}else{
#if DEBUG_VERBOSE_EL
				Serial.print(F("A("));
				Serial.print(analog_pin_state[i].number);
				Serial.print(F(","));
				Serial.print(analog_pin_state[i].value);
				Serial.print(F(","));
				Serial.print(value);
				Serial.print(F(") "));
#endif
			}

		}
	}
}

//*****************************************************************************************************************//

void ExecutionLayer :: execute_action(ControlPacket p)
{
	packet = p;
#if DEBUG
	Serial.println(F("STARTING EXECUTION"));
#endif

	if(packet.baudrate == 0){
		set_pin_to_value();

	}else{
		send_serial_data();
	}
}
//*****************************************************************************************************************//
void ExecutionLayer::read_serial_value(int pin){

	unsigned char rx = (unsigned char)pin;
	unsigned char tx = 255; //unused

	SoftwareSerial device(rx,tx);

	device.listen();

	while(device.available() < 1);

	unsigned char number_of_read_bytes = device.available();
#if DEBUG
	Serial.write(number_of_read_bytes);
#endif

	if(number_of_read_bytes > MAX_NUMBER_OF_READ_BYTES)
	{
		number_of_read_bytes = MAX_NUMBER_OF_READ_BYTES;
	}

	for(register int i = 0; i < number_of_read_bytes; i++)
	{
		read_bytes[i] = device.read();
	}


	delay(1000);

	while(device.available() > 0)
	{
		device.read();
	}
	device.read();
#if DEBUG
	for(register int i = 0; i < number_of_read_bytes; i++)
	{
		Serial.write(read_bytes[i]);
	}

	Serial.write(0xDD);
#endif
}

//*****************************************************************************************************************//
void ExecutionLayer :: set_pin_to_value()
{
	if(packet.pin_type == DIGITAL)
	{
#if DEBUG
		Serial.println("Type: DIGITAL");
#endif
		if(is_in_digital_pins(packet.pin_number))
		{
#if DEBUG
			Serial.print("Writing value ");
			Serial.print((packet.info[0]!=0x00)?HIGH:LOW);
			Serial.print(" to digital pin number ");
			Serial.println(packet.pin_number);

#endif
			pinMode(packet.pin_number,OUTPUT);
			digitalWrite(packet.pin_number,(packet.info[0]!=0x00)?HIGH:LOW);
			CL.send_ack(OK,packet);

		}else{
#if DEBUG
			Serial.print("invalid pin for digital:");
			Serial.println(packet.pin_number);
#endif

			CL.send_ack(FAIL,packet);
		}
	}

	else if(packet.pin_type == PWM || packet.pin_type == ANALOG)
	{
		if(is_in_pwm_pins(packet.pin_number))
		{
#if DEBUG
			Serial.println("Type: PWM");
#endif
			pinMode(packet.pin_number,OUTPUT);
			analogWrite(packet.pin_number,packet.info[0]);
			CL.send_ack(OK,packet);
		}
		else
		{
			CL.send_ack(FAIL,packet);
		}
	}else{
#if DEBUG
		Serial.println("Type: UNKNOWN");
#endif
	}

	return;
}

//*****************************************************************************************************************//
bool ExecutionLayer :: is_in_digital_pins(unsigned char pin_no)
{

#if ARDUINO_UNO
	unsigned char writable_digital_pins [] = {0,1,2,3,4,5,6,7,9,12,13};
#endif
#if ARDUINO_MEGA
	unsigned char writable_digital_pins [] = {0,1,2,3,4,5,6,7,8,9,12,13,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53};
#endif
	int s = sizeof(writable_digital_pins)/sizeof(char);

	#if DEBUG
	Serial.print("size of digital pins:");
	Serial.println(s);
#endif

	//For stability weird reasons
	delay(1);
	for(register unsigned char i = 0; i < s ; i++)
	{
#if DEBUG_VERBOSE_EL
		Serial.print("----testing pins if ");
		Serial.print(pin_no);
		Serial.print(" (");
		Serial.print((int)pin_no);
		Serial.print(") is qual to ");
		Serial.print(writable_digital_pins[i]);
#endif
		if((int)pin_no == writable_digital_pins[i])
		{
#if DEBUG
			Serial.println("pin is in digital range!");
#endif
			return 1;
		}
		else
		{
#if DEBUG_VERBOSE_EL
			Serial.println(": Different...");
#endif
			continue;
		}
	}
#if DEBUG
	Serial.println("----TEST FAILED.");
#endif
	return 0;
}

//*****************************************************************************************************************//
bool ExecutionLayer :: is_in_pwm_pins(unsigned char pin_no)
{
#if ARDUINO_UNO
	unsigned char writable_pwm_pins  [] = {3,5,6,9};
#endif
#if ARDUINO_MEGA
	unsigned char writable_pwm_pins  [] = {0,1,2,3,4,5,6,7,8,9,12,13};
#endif
	int s = sizeof(writable_pwm_pins)/sizeof(char);
#if DEBUG
	Serial.print("size of pwm pins:");
	Serial.println(s);
#endif

	//For stability weird reasons
	delay(1);
	for(register unsigned char i = 0; i < s ; i++)
	{
#if DEBUG_VERBOSE_EL
		Serial.print("----testing if ");
		Serial.print(pin_no);
		Serial.print(" (");
		Serial.print((int)pin_no);
		Serial.print(") is qual to ");
		Serial.print(writable_pwm_pins[i]);
#endif
		if(pin_no == writable_pwm_pins[i])
		{
#if DEBUG
			Serial.println(": Pin in PWM allowed! TEST FINISHED");
#endif
			return 1;
		}
		else
		{
#if DEBUG_VERBOSE_EL
			Serial.println(": Different...");
#endif
			continue;
		}
	}
#if DEBUG
	Serial.println("----TEST FAILED.");
#endif
	return 0;
}


//*****************************************************************************************************************//
void ExecutionLayer:: send_serial_data()
{
	if(is_in_digital_pins(packet.pin_number))
	{
		unsigned char tx = packet.pin_number;
		unsigned char rx = 255; //unused


		SoftwareSerial device(rx,tx);

		device.begin(packet.baudrate);

		//device.listen();        //redundant

		for(register unsigned char i = 0; i < packet.length_of_info; i++)
		{
			device.write(packet.info[i]);
		}
		CL.send_ack(OK,packet);
	}
	else
	{
		CL.send_ack(FAIL,packet);
	}
	return;
}
//*****************************************************************************************************************//
void ExecutionLayer::notify(int signal)
{
	pinMode(12,OUTPUT);
	if(signal == 0){
		digitalWrite(12,LOW);
		delay(1000);
		digitalWrite(12,HIGH);
		delay(1000);
		digitalWrite(12,LOW);
		delay(1000);
		digitalWrite(12,HIGH);
	}else if(signal == 1){
		digitalWrite(12,LOW);
		delay(500);
		digitalWrite(12,HIGH);
		delay(1000);
		digitalWrite(12,LOW);
		delay(500);
		digitalWrite(12,HIGH);
	}else if(signal == 2){
		digitalWrite(12,LOW);
		delay(300);
		digitalWrite(12,HIGH);
	}
	else if(signal == 3){
		digitalWrite(12,HIGH);
		delay(300);
		digitalWrite(12,LOW);
	}
}


