#include <Arduino.h>
#include <SoftwareSerial.h>
#include "CONFIG_Config.h"
#include "LAYERS_CommunicationLayer.h"
#include "LAYERS_ExecutionLayer.h"

void setup(){
#if DEBUG
	Serial.begin(DEBUG_MONITOR_BAUDRATE);
	Serial.println(F("Debug mode is on in main."));
#endif
	CL.check_debug();
	EL.check_debug();
}

void loop(){
	//read zigbee
	CL.monitor();

	//check pin state every second
	EL.monitor();
}
