#ifndef CONFIG_CONFIG_H_
#define CONFIG_CONFIG_H_

//Board configuration
#define	ARDUINO_UNO    					false          	// Sets pin Configuration for Arduinp UNO
#define ARDUINO_MEGA    				true           	// Sets pin Configuration for Arduinp MEGA

//ZigBee module configuration
#define ZIGBEE_BAUDRATE 				19200			// Baud rate to communicate with ZigBee module
#define ZIGBEE_TX 						11				// Transmission pin to XBEE MODULE
#define ZIGBEE_RX 						10				// RECEPTION pin from XBEE MODULE

//Checks time to reread pins to check for new states
#define RECHECK_PIN_STATE_INTERVAL		1000			//Interval, in milliseconds, to read pins to check for new states

//Sets buffer retransmission rate
#define EVENT_PACKET_RESEND_INTERVAL	3000			//Interval, in milliseconds, to wait for ACK before resending packet.
#define EVENT_PKT_SRAM_AVAILABLE_SPACE	3000			//Amount of SRAM that event packet buffer should try to leave free.
#define EVENT_PACKET_BUFFER_MAX_LENGTH	100				/*Maximum quantity of packets to keep in SRAM to try to resend. Notice
														  that if there is no available SRAM and Maximum length has not been reached,
														  available length in SRAM will determine buffer size. In other words,
														  EVENT_PACKET_BUFFER_LENGTH will be respected only
														  if(EVENT_PACKET_BUFFER_LENGTH <= EVENT_PKT_SRAM_AVAILABLE_SPACE)
														  other ways, it will be reduced to EVENT_PKT_SRAM_AVAILABLE_SPACE.
														  Each packet occupies around 16 bytes of SRAM.*/

//Sets debug configuration
#define DEBUG 							false			//Sets if should include debugging code. Allows debugging, but turns Arduino very slow.
#define DEBUG_MONITOR_BAUDRATE 			19200			//Sets Baud Rate to communicate with debug monitor
#define DEBUG_VERBOSE_EL 				false			//Sets a verbose debug of configuration module
#define DEBUG_CHECK_ANALOG_PINS			false			//Sets if code will check state change on analog pins



#endif /* CONFIG_CONFIG_H_ */
