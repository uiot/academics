#ifndef MEMORY_HANDLER_H
#define MEMORY_HANDLER_H

#include <avr/pgmspace.h>

class MemoryHandler{
public:
	int get_free_sram();
	//unsigned char EEPROM_read(int address);
	//void EEPROM_write(int address, unsigned char value);
};

#endif // MEMORY_HANDLER_H
