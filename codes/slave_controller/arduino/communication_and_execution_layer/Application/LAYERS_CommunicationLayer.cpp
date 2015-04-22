#include "LAYERS_CommunicationLayer.h"


SoftwareSerial XBEE(ZIGBEE_RX,ZIGBEE_TX);// (Rx, Tx)

Communication_Layer CL = Communication_Layer();

//*****************************************************************************************************************//
void Communication_Layer::check_debug(){
#if DEBUG
	Serial.println(F("Debug mode is on in Communication Layer."));
#endif
}

//*****************************************************************************************************************//
Communication_Layer::Communication_Layer(){
	event_packet_buffer = new EventBuffer();
	XBEE.begin(ZIGBEE_BAUDRATE);
	id = 1;//Starts at one because packets with id 0 is considered null packet.
	latest_event_packet_resend = millis();
	XBEE.listen();
}

//*****************************************************************************************************************//
void Communication_Layer::monitor()
{
	//if has enough bytes to read first static fields of packet, do it.
	if(XBEE.available() > 2)
	{
#if DEBUG
		Serial.println(F("NEW ZIGBEE PACKET."));
#endif

		//if first byte is action packet request, read packet.
		unsigned char start_delimeter = XBEE.read();
		if(start_delimeter == CTRL_INI){
#if DEBUG
			Serial.println(F("NEW UIoT CONTROL PACKET"));
#endif

			//gets other packet info
			ControlPacket p = get_control_packet();

			//execute packet
			EL.execute_action(p);
#if DEBUG
			Serial.println(F("FINISHED UIoT CONTROL PACKET"));
#endif
		}else if(start_delimeter == EVENT_INI){
#if DEBUG
			Serial.println(F("NEW UIoT EVENT ACK PACKET"));
#endif
			//receives ack packet and remove it from buffer.
			treat_event_ack();
#if DEBUG
			Serial.println(F("FINISHED UIoT EVENT PACKET"));
#endif

		}
#if DEBUG
		Serial.println(F("FINISHED ZIGBEE PACKET."));
#endif
	}else{
		//Case there is nothing to read, resend all event packets in buffer that have not received ACK yet in EVENT_PACKET_RESEND_INTERVAL milliseconds.
		unsigned long current_millis = millis();
		if(current_millis - latest_event_packet_resend > EVENT_PACKET_RESEND_INTERVAL){
			latest_event_packet_resend = current_millis;
			const EventPacket *p = event_packet_buffer->get_first();
			while(p != NULL){
				if(current_millis - p->last_transmission > EVENT_PACKET_RESEND_INTERVAL){
#if DEBUG
					Serial.print(F("EVENT PACKET WITHOUT ACK IN "));
					Serial.print(EVENT_PACKET_RESEND_INTERVAL);
					Serial.print(F(" MILISECONDS! (id = "));
					Serial.print(p->id);
					Serial.println(F(") RESENDING IT!!!"));
#endif
					notify_new_value((int)p->pin_number,(int)p->pin_number,p->id);
					//stability
					delay(10);
					if(XBEE.available() > 2)
						break;
				}
				p = p->next;
			}
			delete p;
			p = NULL;
		}
	}
}

//*****************************************************************************************************************//
void Communication_Layer::treat_event_ack(){
	//receives packet id
	unsigned char msb = XBEE.read();
	unsigned char lsb = XBEE.read();
	unsigned int packet_id = ((int)(msb<<8))+((int)lsb);

	//receive message status
	bool status = (XBEE.read()==0x00)?true:false;

	//if it is a SUCCESS ack, remove packet from resend buffer.
	if(status)
		event_packet_buffer->remove(packet_id);


#if DEBUG
	Serial.print(F("  - ID: "));
	Serial.println(packet_id);
	Serial.print(F("  - STATUS: "));
	Serial.println((status)?F("SUCCESS"):F("ERROR"));
#endif
}

//*****************************************************************************************************************//
ControlPacket Communication_Layer :: get_control_packet(){
	//reads packet id
	ControlPacket p;

	p.id[0] = XBEE.read();
	p.id[1] = XBEE.read();
#if DEBUG
	Serial.print(F("  - ID:"));
	Serial.print(p.id[0]);
	Serial.print(F("-"));
	Serial.println(p.id[1]);
#endif

	//reads length of data
	unsigned char new_length_byte = XBEE.read();
	unsigned char length_of_data;
	String buffer = "";
#if DEBUG
	Serial.print(F("  - INFO LENGTH: "));
	Serial.print(new_length_byte);
	Serial.print(F("... "));
#endif

	int i = 0;
	while(true){
		buffer+=new_length_byte;
		new_length_byte = XBEE.read();
		if(new_length_byte == 0x00)
			break;
#if DEBUG
		Serial.print(new_length_byte);
		Serial.print(" ");
#endif

		while(XBEE.available()<1);
	}
	p.length_of_info = (unsigned char)buffer.charAt(0) - '0';//adjust to subtract 0 ASCII (49)

#if DEBUG
	Serial.print(F(" Detailed final view: "));
	Serial.print((int)p.length_of_info);
	Serial.print(F("."));
	Serial.print(buffer.charAt(0));
	Serial.print(F("."));
	Serial.println(p.length_of_info);
#endif

	//waits for the rest of data headers  to come
	while(XBEE.available() < 2);


	//Gets headers and data
	p.pin_number = XBEE.read();
#if DEBUG
	Serial.print(F("  - PIN_NUMBER:"));
	Serial.println(p.pin_number);
#endif

	unsigned char custom_byte = XBEE.read();
#if DEBUG
	Serial.print(F("  - Custom Byte:"));
	Serial.println(custom_byte);
#endif

	p.pin_type = get_operation_mode(custom_byte);
#if DEBUG
	Serial.print(F("  - PIN_TYPE:"));
	Serial.println(p.pin_type);
#endif

	p.data_type = get_data_type(custom_byte);
#if DEBUG
	Serial.print(F("  - DATA_TYPE:"));
	Serial.println(p.data_type);
#endif

	p.baudrate = get_baud_rate(custom_byte);
#if DEBUG
	Serial.print(F("  - BAUDRATE:"));
	Serial.println(p.baudrate);
#endif

#if DEBUG
	Serial.print(F("  - VALUE:("));
#endif
	for(register unsigned char i = 0 ; i < p.length_of_info ; i++){
		//waits for next byte of data
		while(XBEE.available()<1);
		//gets data
		p.info[i] = XBEE.read();
#if DEBUG
		Serial.print(p.info[i]);
#endif
	}
#if DEBUG
	Serial.println(F(")"));
#endif

#if false
	XBEE.write(0xcc);
	XBEE.write(p.length_of_info);
	XBEE.write(p.pin_number);
	XBEE.write(p.pin_type);
	XBEE.write(p.data_type);
	XBEE.write(p.baudrate);
	for(register unsigned char i = 0 ; i < p.length_of_info ; i++)
	{
		XBEE.write(p.info[i]);
	}
#endif

	return p;
}

//****************************************************************************************************************//
int Communication_Layer :: get_baud_rate(unsigned char custom_byte)
{
	unsigned char baud_rate_bits = custom_byte%16;//gets lowest 4 bits -> 00001111
#if DEBUG
	Serial.print(F("      - Custom Byte's baud rate bits:"));
	Serial.println(baud_rate_bits);
#endif
	switch(baud_rate_bits)
	{
	case (0x00):
																														return 0;
	case (0x01):
																														return 75;
	case (0x02):
																														return 150;
	case (0x03):
																														return 300;
	case (0x04):
																														return 600;
	case (0x05):
																														return 1200;
	case (0x06):
																														return 2400;
	case (0x07):
																														return 4800;
	case (0x08):
																														return 9600;
	case (0x09):
																														return 14400;
	case (0x0A):
																														return 16457;
	case (0x0B):
																														return 19200;
	case (0x0C):
																														return 28800;
	case (0x0D):
																														return 38400;
	case (0x0E):
																														return 57600;
	case (0x0F):
																														return 115200;
	default:
		return 0;
	}
}

//****************************************************************************************************************//
int Communication_Layer :: get_data_type(unsigned char custom_byte)
{
	unsigned char data_type_bits = ((custom_byte-(custom_byte%16))%64)>>4;//3rd and 4th highest bits value-> 00110000
#if DEBUG
	Serial.print(F("      - Custom Byte's data type bits:"));
	Serial.println(data_type_bits);
#endif
	switch(data_type_bits)
	{
	case (0x00):
																														return INT;
	case (0x01):
																														return BOOLEAN;
	case (0x02):
																														return STRING;
	case (0x03):
																														return LONG;
	default:
		return INT;
	}
}

//****************************************************************************************************************//
int Communication_Layer :: get_operation_mode(unsigned char custom_byte)
{
	unsigned char operation_mode_bits = (custom_byte-(custom_byte%64))>>6;//gets 2 highest bits value-> 11000000
#if DEBUG
	Serial.print(F("      - Custom Byte's operation mode bits:"));
	Serial.println(operation_mode_bits);
#endif
	switch(operation_mode_bits)
	{
	case (0x00):
																																	return DIGITAL;
	case (0x01):
																																	return ANALOG;
	case (0x02):
																																	return PWM;
	case (0x03):
																																	return UART;
	default:
		return DIGITAL;

	}
}

//*****************************************************************************************************************//
void Communication_Layer :: send_ack(unsigned char ack, ControlPacket p)
{
#if DEBUG
	Serial.print(F("		+ SENDING ACK FOR PACKET id"));
	int p_id = (((int) p.id[0])<< 8 ) + (int) p.id[1];
	Serial.print(p_id);
	Serial.println("!");
	Serial.print(F("		+ Available SRAM is "));
	MemoryHandler mh;
	Serial.print(mh.get_free_sram());
	Serial.println(" bytes!");
#endif
	XBEE.write(CTRL_INI);					//field 1 - ack ini
	XBEE.write((unsigned char)p.id[0]);			//field 2 - msg id msb
	XBEE.write((unsigned char)p.id[1]);			//field 2 - msg id lsb
	XBEE.write(ack);							//field 3 - status of request
	return;
}

//****************************************************************************************************************//
void Communication_Layer::add_packet_to_buffer(unsigned int packet_id,int pin_number, int pin_value){
	//creates new packet
	EventPacket p;
	p.id = packet_id;
	p.pin_number = (unsigned char) pin_number%256;
	p.pin_value = (unsigned char) pin_value%256;
	p.last_transmission = millis();

	//adds packet to buffer.
	//this method first checks if packet is new, case it is, it saves it. Otherwise, it updates the last transmission time.
	bool result = event_packet_buffer->append(p);

#if DEBUG
	Serial.print(F("		+ Buffer new length is "));
	unsigned int l = event_packet_buffer->length();
	Serial.print(l);
	Serial.println("!");
	Serial.print(F("		+ Available SRAM is "));
	MemoryHandler mh;
	Serial.print(mh.get_free_sram());
	Serial.println(" bytes!");
#endif
}

//****************************************************************************************************************//
//send used_id < 0 to let this function generate message id automatically.
void Communication_Layer::notify_new_value(int pin_number, int pin_value, int used_id){
#if DEBUG
	if(used_id < 0){
		Serial.print(F("NEW VALUE ON PIN "));
		Serial.print(pin_number);
		Serial.print(F(" IS "));
		Serial.print(pin_value);
	}
#endif
	//decides if uses parameter id or auto generate id
	unsigned int msb_lsb = (used_id >= 0)?used_id:id;

	//don't let id be bigger then 2 bytes
	if(msb_lsb > 0xFFFF)
		msb_lsb = msb_lsb%0xFFFF;

	//gets id msb and lsb
	int msb = ((msb_lsb-msb_lsb%256) << 8) %256;
	int lsb = msb_lsb%256;

	//builds packet
	XBEE.write(EVENT_INI);				//field 1 - init
	XBEE.write((unsigned char)msb);		//field 2 - msg id msb
	XBEE.write((unsigned char)lsb);		//field 2 - msg id lsb
	XBEE.write((unsigned char)0x01);	//field 3 - length
	XBEE.write((unsigned char)0x00);	//field 3 - length end
	XBEE.write(pin_number);				//field 4 - pin_number
	XBEE.write(pin_value);				//field 6 - pin_value

#if DEBUG
	Serial.print(F(" - sent through xbee and saved in buffer with id "));
	Serial.println(msb_lsb);
#endif

	//saves packet for retransmission
	add_packet_to_buffer(msb_lsb,pin_number,pin_value);

	//handles auto generated id to increment it
	if(used_id <= 0){
		if(id > 0xFFFE)
			id = 0;
		else
			id++;
	}
	delay(10);
}
