#ifndef EVENT_PACKET_H
#define EVENT_PACKET_H

#include <stdlib.h>

class EventPacket{
public:
	unsigned int id; 					 //2 bytes
	unsigned char pin_number;			 //1 byte
	unsigned char pin_value;			 //1 byte
	unsigned long int last_transmission; //4 bytes
	EventPacket();
	EventPacket * next;					 //4 bytes
	EventPacket * previous;				 //4 bytes
	~EventPacket();
};

#endif /* EVENT_PACKET_H */
