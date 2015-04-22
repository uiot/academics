#ifndef COMMUNICATION_LAYER_H
#define COMMUNICATION_LAYER_H

#include <Arduino.h>
#include <SoftwareSerial.h>
#include <string.h>

#include "CONFIG_Config.h"
#include "HELPERS.h"
#include "LAYERS_ExecutionLayer.h"

//Packet initiator
#define  CTRL_INI		0xFF        //UIoT Control Packet ini byte (ACK and message)
#define  EVENT_INI		0xAA        //UIoT Event Packet ini byte (ACK and message)

//Control ACK Response data indicating if control was right received.
#define  OK             0x00        //If OK send 0x00
#define  FAIL           0xFF        //If FAIL send 0xFF

//interfaces type(interfaces = pin, for Arduino.)
#define  DIGITAL	    0x10
#define  UART		    0x10
#define  ANALOG         0x11
#define  PWM 		    0x12

//Data types
#define  INT            0x20
#define  BOOLEAN        0x21
#define  STRING         0x22
#define  LONG         	0x23

class Communication_Layer
{
public:
	unsigned int id;
	ControlPacket get_control_packet();
	void notify_new_value(int pin_number, int pin_value, int type);
	void monitor();
	void check_debug();
	void send_ack(unsigned char,ControlPacket p);
	void add_packet_to_buffer(unsigned int id, int pin, int value);
	int get_baud_rate(unsigned char custom_byte);
	int get_operation_mode(unsigned char custom_byte);
	int get_data_type(unsigned char custom_byte);
	EventBuffer* event_packet_buffer;
	Communication_Layer();
	void treat_event_ack();
private:
	unsigned long int latest_event_packet_resend;
};



extern Communication_Layer CL;

#endif // COMMUNICATION_LAYER_H
