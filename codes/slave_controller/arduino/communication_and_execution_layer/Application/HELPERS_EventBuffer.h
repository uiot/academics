#ifndef EVENT_BUFFER_H
#define EVENT_BUFFER_H

#include <Arduino.h>
#include <SoftwareSerial.h>
#include <stdlib.h>
#include "HELPERS_MemoryHandler.h"
#include "PACKETS_EventPacket.h"
#include "CONFIG_Config.h"

//This class holds a packet chain of EventPackets to buffer packets with ack not received from Master controller.
class EventBuffer{
public:
    EventBuffer();
    const EventPacket* get_next();
    const EventPacket* get_previous();
    const EventPacket* get_first();
    bool append(EventPacket packet);
    bool remove(unsigned int packet_id);
    void point_to_start();
    void point_to_end();
    bool point_to_index(int index);
    bool point_to_id(unsigned int pakcte_id);
    unsigned int length();
private:
    EventPacket* pointer;
    EventPacket* first;
    EventPacket* last;
};

#endif /* EVENT_BUFFER_H */
