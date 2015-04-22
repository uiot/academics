#ifndef CONTROL_PACKET_H
#define CONTROL_PACKET_H

#include <stdlib.h>

#define UIOT_DATA_LENGTH 512

class ControlPacket{
public:
	ControlPacket();
	unsigned char id[2];
	unsigned char length_of_info;
	unsigned char pin_number;
	unsigned char pin_type;
	unsigned char data_type;
	unsigned long int baudrate;
	unsigned char info[UIOT_DATA_LENGTH];
	ControlPacket* next;
	ControlPacket* previous;
};


#endif /* CONTROL_PACKET_H */
