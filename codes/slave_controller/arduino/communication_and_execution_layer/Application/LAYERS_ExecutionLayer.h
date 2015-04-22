#ifndef EXECUTION_LAYER_H
#define EXECUTION_LAYER_H

#include <Arduino.h>
#include <SoftwareSerial.h>

#include "CONFIG_Config.h"
#include "HELPERS.h"
#include "LAYERS_CommunicationLayer.h"


#define  MAX_NUMBER_OF_READ_BYTES   5

class ExecutionLayer{
public:
	ControlPacket packet;
	long previousMillis;
	Interface * digital_pin_state;
	Interface * analog_pin_state;
	unsigned char read_bytes[5];

	void monitor();
	void execute_action(ControlPacket p);
	void send_serial_data();
	void set_pin_to_value ();
	bool is_in_pwm_pins(unsigned char);
	bool is_in_digital_pins(unsigned char);
	void check_debug();
	ExecutionLayer();
	void notify(int signal);

private:
	void read_serial_value(int pin);
};

extern ExecutionLayer EL;
#endif /* EXECUTION_LAYER_H */

