#include "HELPERS_MemoryHandler.h"

//************************************************************************************************
//Checks amount of free SRAM.
int MemoryHandler::get_free_sram(){
  extern int __heap_start, *__brkval;
  int v;
  return (int) &v - (__brkval == 0 ? (int) &__heap_start : (int) __brkval);
}


//************************************************************************************************
//Write a byte to EEPROM
/*
void MemoryHandler::EEPROM_write(int address, unsigned char value)
{
#ifndef definde EPROM_ADDED
#define EPROM_ADDED true
#include <EEPROM.h>
#endif
      EEPROM.write(address++, value);
}

//************************************************************************************************
//Read a byte from values from EEPROM
unsigned char MemoryHandler::EEPROM_read(int address)
{
#ifndef definde EPROM_ADDED
#define EPROM_ADDED true
#include <EEPROM.h>
#endif
	unsigned char value = (unsigned char)EEPROM.read(address);
   return value;
}
*/
