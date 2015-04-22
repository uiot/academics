#include "HELPERS_EventBuffer.h"
//****************************************************************************************************************//
//Initializer. it sets pointers to null in order to let ifs and elses work without getting nullpointer exception!
EventBuffer::EventBuffer(){
	pointer = NULL;
	first = NULL;
	last = NULL;
}
//****************************************************************************************************************//
//Moves pointer to next element in packets chain and returns such element. If no element is found, return false.
const EventPacket* EventBuffer::get_next(){
#if DEBUG
	//Serial.println(F("            + Getting next packet in buffer..."));
#endif
	if(pointer != NULL){
		pointer = pointer->next;
		if(pointer != NULL){
			return pointer->previous;
		}else if(last != NULL){
			return last;
		}
	}
	return NULL;
}
//****************************************************************************************************************//
//Moves pointer to previous element in packets chain and returns such element. If no element is found, return false.
const EventPacket* EventBuffer::get_previous(){
#if DEBUG
	// Serial.println(F("            + Getting previous packet in buffer..."));
#endif
	if(pointer != NULL){
		pointer = pointer->previous;
		if(pointer != NULL){
			return pointer->next;
		}else if(first != NULL){
			return first;
		}
	}
	return NULL;
}
//****************************************************************************************************************//
//Moves pointer to first element in packets chain and returns such element. If no element is found, return false.
const EventPacket* EventBuffer::get_first(){
	return first;
}
//****************************************************************************************************************//
//case it has not been added before, adds a packet to the end of the chain.
bool EventBuffer::append(EventPacket p){
	MemoryHandler mh;

	//checks if it is possible to add more packets to SRAM.
	//Minimum value for free SRAM will be 600 bytes.
	unsigned int l = length();
	int sram = mh.get_free_sram();
	if((sram < EVENT_PKT_SRAM_AVAILABLE_SPACE) || (l >=  EVENT_PACKET_BUFFER_MAX_LENGTH)){
		//case buffer is clean(length zero) and there is no SRAM available, do not append packets.
		if(l == 0){
#if DEBUG
			Serial.println(F("				+ NOT Appending packet to buffer..."));
			Serial.print(F("				+ Available SRAM before append to buffer is "));
			Serial.println(mh.get_free_sram());
#endif
			return false;
		}
		//Other ways, remove first element from buffer.
		remove(first->id);
	}
	//#if DEBUG
	//	Serial.println(F("				+ Appending packet to buffer..."));
	//	Serial.print(F("				+ Available SRAM before append to buffer is "));
	//	Serial.println(mh.get_free_sram());
	//#endif

	if(!point_to_id(p.id)){
		if(first == NULL && last == NULL){
			first = new EventPacket();
			*first = p;
			last = first;
		}else{
			p.previous = last;
			last->next = new EventPacket();
			*last->next = p;
			last = last->next;
		}
		//#if DEBUG
		//Serial.print(F("				+ After appending, Free SRAM is: "));
		//Serial.println(mh.get_free_sram());
		//#endif
		return true;
	}else{
		pointer->last_transmission = p.last_transmission;
		//#if DEBUG
		//Serial.print(F("          + Updated Trasmission time. Free SRAM now is: "));
		//Serial.println(mh.get_free_sram());
		//#endif
		return false;
	}
}


//****************************************************************************************************************//
//removes a packet with "packet_id" from packet chain.
bool EventBuffer::remove(unsigned int packet_id){
	EventPacket* packet = first;
#if DEBUG
	int sram;
	MemoryHandler mh;
	Serial.print(F("            	+ Removing packet from buffer... SRAM reduced from "));
	sram = mh.get_free_sram();
	unsigned int buffer = length();
	Serial.print(sram);
#endif
	while(packet!=NULL){
		if(packet->id == packet_id){
			if(packet == first && first->next == NULL){
				delete first;
				first = NULL;
				last = NULL;
			}else if(packet == first){
				first = first->next;
				delete first->previous;
				first->previous = NULL;
			}else if(packet == last){
				last = last->previous;
				delete last->next;
				last->next = NULL;
			}else{
				packet = packet->next;
				packet->previous = packet->previous->previous;
				delete packet->previous->next;
				packet->previous->next = NULL;
				packet->previous->next = packet;
			}
			packet = NULL;
#if DEBUG
			sram = mh.get_free_sram();
			Serial.print(F(" to "));
			Serial.print(sram);
			Serial.println(F(" bytes."));
#endif
			return true;
		}else{
			packet = packet->next;
		}
	}
#if DEBUG
	MemoryHandler mh2;
	int sram2 = mh2.get_free_sram();
	Serial.print(F(" to "));
	Serial.print(sram2);
	Serial.print(F(" bytes and buffer from "));
	Serial.print(buffer);
	Serial.print(F(" to "));
	unsigned int buffer2 = length();
	Serial.print(buffer2);
	Serial.println(F(" packets."));


#endif
	return false;
}

//****************************************************************************************************************//
//Moves pointer to first element of packet chain.
void EventBuffer::point_to_start(){
#if DEBUG
	// Serial.println(F("            + Pointing packet buffer to start..."));
#endif
	pointer = first;
}

//****************************************************************************************************************//
//Moves pointer to last element of packet chain.
void EventBuffer::point_to_end(){
#if DEBUG
	// Serial.println(F("            + Pointing packet buffer to end..."));
#endif
	pointer = last;
}

//****************************************************************************************************************//
//Moves pointer to element in "index_id" of packet chain. Case index does not exist, returns false, other ways, return true.
bool EventBuffer::point_to_index(int index_id){
#if DEBUG
	//  Serial.println(F("            + Pointing packet buffer to index..."));
#endif
	point_to_start();
	int i = 0;
	while(pointer != NULL){
		if(i==index_id)
			return true;
		pointer = pointer->next;
		i+=1;
	}
	return false;
}

//****************************************************************************************************************//
//Moves pointer to packet with id "packet_id" of packet chain. Case [acket_id does not exist, returns false, other ways, return true.
bool EventBuffer::point_to_id(unsigned int packet_id){
#if DEBUG
	//Serial.println(F("            + Pointing packet buffer to id..."));
#endif
	point_to_start();
	while(pointer != NULL){
		if(pointer->id == packet_id)
			return true;
		pointer = pointer->next;
	}
	return false;
}

//****************************************************************************************************************//
//Checks if packet with "packet_id" exists in chain
unsigned int EventBuffer::length(){
#if DEBUG
	//   Serial.println(F("            + Getting buffer length..."));
#endif
	unsigned int length = 0;
	point_to_start();
	while(get_next() != NULL){
		length++;
	}
	return length;
}
