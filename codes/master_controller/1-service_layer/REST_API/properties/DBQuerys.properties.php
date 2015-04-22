<?php

$query_create_db = "CREATE DATABASE IF NOT EXISTS 'uiot' ";

$query_create_slave_controller_tb = "CREATE TABLE IF NOT EXISTS `slave_controller` (
						`PK_Unic_Name` varchar(255) NOT NULL,
						`TE_Type` text NOT NULL,
					        `TE_Address` text,						
						`TE_Description` text,
						`BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$query_create_action_tb = "CREATE TABLE IF NOT EXISTS `action` (
						`PK_Id` int(11) NOT NULL,
						`FK_Service` int(11) NOT NULL,
						`TE_Name` varchar(32) NOT NULL,
						`BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9";

$query_create_argument_tb = "CREATE TABLE IF NOT EXISTS `argument` (
						`PK_Id` int(11) NOT NULL,
						`FK_Action` int(11) NOT NULL,
						`TE_Name` varchar(32) NOT NULL COMMENT 'REQUIRED. Name of formal parameter. The name SHOULD be 							 chosen to reflect the semantic use of the argument. MUST NOT contain a hyphen character (“-”, 2D 							 Hex in UTF-8). First character MUST be a USASCII letter (“A”-“Z”, “a”-“z”), USASCII digit 							 (“0”-“9”), an underscore (“_”), or a non- experimental Unicode letter or digit greater than U+007F. 							Succeeding characters MUST be a USASCII letter (“A”-“Z”, “a”-“z”), USASCII digit (“0”-“9”), an underscore (“_”), a period (“.”), a Unicode combiningchar, an extender, or a non-experimental Unicode letter or digit greater than U+007 . The first three letters MUST NOT be “XML” in any combination of case. String. Case sensitive. SHOULD be < 32 characters.',
					    `EN_Direction` enum('in','out') NOT NULL COMMENT 'REQUIRED. Defines whether argument is an input or out									put parameter.\nMUST be either “in” or “out” and not both. All input arguments MUST be listed before a								   ny output arguments.\n',
						`TE_Ret_Val` text COMMENT 'OPTIONAL. Identifies at most one output argument as the return value. If inc									luded, MUST be included as a subelement of the first output argument. (Element only; no value.)\n',
						`FK_Related_State_Variable` int(11) NOT NULL,
						`BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
						 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13" ;


$query_create_device_tb = "CREATE TABLE IF NOT EXISTS `device` (
							`PK_Id` int(11) NOT NULL COMMENT 'REQUIRED. Unique Device Name. Universally-unique identifier for the device, whether root or embedded. MUST be the same over time for a specific device instance (i.e., MUST survive reboots). MUST match the field value of the NT header field in device discovery messages. MUST match the prefix of the USN header field in all discovery messages. (Section 1, “Discovery” explains the NT and USN header fields.) MUST begin with “uuid:” followed by a UUID suffix specified by a UPnP vendor. See section 1.1.4, “UUID format and RECOMMENDED generation algorithms” for the MANDATORY UUID format.\n',
  							`TE_UDN` varchar(255) NOT NULL,
  							`FK_Slave_Controller` varchar(255) NOT NULL,
  							`TE_Friendly_Name` text NOT NULL COMMENT 'REQUIRED. Short description for end user. MAY be localized (see ACCEPT-LANGUAGE and CONTENT- LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 64 characters.\n',
 						    `TE_Device_Type` text NOT NULL COMMENT 'REQUIRED. UPnP device type. Single URI.\n •   For standard devices defined by a UPnP Forum working committee, MUST begin with “urn:schemas- upnp-org:device:” followed by the standardized device type suffix, a colon, and an integer device version i.e. urn:schemas-upnp-org:device:deviceType:ver. The highest supported version of the device type MUST be specified. \n •   For non-standard devices specified by UPnP vendors, MUST begin with “urn:”, followed by a Vendor Domain Name, followed by “:device:”, followed by a device type suffix, colon, and an integer version, i.e., “urn:domain-name:device:deviceType:ver”. Period characters in the Vendor Domain \n          \n44\nName MUST be replaced with hyphens in accordance with RFC 2141. The highest supported version of the device type MUST be specified.\nThe device type suffix defined by a UPnP Forum working committee or specified by a UPnP vendor MUST be <= 64 chars, not counting the version suffix and separating colon.',
  							`TE_Manufacturer` text NOT NULL COMMENT 'REQUIRED. Manufacturer''s name. MAY be localized (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 64 characters.\n',
  							`TE_Manufacturer_URL` text COMMENT 'OPTIONAL. Web site for Manufacturer. MAY have a different value depending on language requested (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. Single URL.\n',
  							`TE_Model_Description` text COMMENT 'RECOMMENDED. Long description for end user. MAY be localized (see ACCEPT-LANGUAGE and CONTENT- LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 128 characters.\n',
  							`TE_Model_Name` text NOT NULL COMMENT 'REQUIRED. Model name. MAY be localized (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 32 characters.\n',
  							`TE_Model_Number` text COMMENT 'RECOMMENDED. Model number. MAY be localized (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 32 characters.\n',
  							`TE_Model_URL` text COMMENT 'OPTIONAL. Web site for model. MAY have a different value depending on language requested (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. Single URL.\n',
  							`TE_Serial_Number` text COMMENT 'RECOMMENDED. Serial number. MAY be localized (see ACCEPT-LANGUAGE and CONTENT-LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 64 characters.\n', 
  							`TE_UPC` text COMMENT 'OPTIONAL. Universal Product Code. 12-digit, all-numeric code that identifies the consumer package. Managed by the Uniform Code Council. Specified by UPnP vendor. Single UPC.\n',
  							`IN_Spec_Version_Major` int(11) NOT NULL DEFAULT '1' COMMENT 'REQUIRED. In device templates, defines the lowest version of the architecture on which the device can be implemented. In actual UPnP devices, defines the architecture on which the device is implemented. Contains the following sub elements:\nREQUIRED. Major version of the UPnP Device Architecture. MUST be 1 for devices implemented on a UPnP 1.1 architecture.\n',
  							`IN_Spec_Version_Minor` int(11) NOT NULL DEFAULT '1' COMMENT 'REQUIRED. In device templates, defines the lowest version of the architecture on which the device can be implemented. In actual UPnP devices, defines the architecture on which the device is implemented. Contains the following sub elements:\nREQUIRED. Minor version of the UPnP Device Architecture. MUST be 1 for devices implemented on a UPnP 1.1 architecture. MUST accurately reflect the version number of the UPnP Device Architecture supported by the device. Control points MUST be prepared to accept a higher version number than the control point itself implements.\n',
  							`TE_XML_Link` text NOT NULL,
  							`BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16";

$query_create_service_tb = "CREATE TABLE IF NOT EXISTS `service` (
							`PK_Id` int(11) NOT NULL,
  							`FK_Device` int(11) NOT NULL,
  							`TE_Friendly_Name` text NOT NULL,
  							`TE_Description` text NOT NULL,
  							`TE_Service_Type` text NOT NULL,
  							`TE_Service_Id` text NOT NULL,
  							`TE_SCPDURL` text NOT NULL COMMENT '  .   REQUIRED. Directory where service description is located or URL for service description.  MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. Specified by UPnP vendor. Single URL. ',
  							`TE_Control_URL` text NOT NULL COMMENT '  .   REQUIRED. URL for control (see section 3, “Control”). MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. Specified by UPnP vendor. Single URL. \n',
  							`TE_Event_SubURL` text NOT NULL COMMENT ' .   REQUIRED. URL for eventing (see section 4, “Eventing”). MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. MUST be unique within the device; any two services MUST NOT have the same URL for eventing. \n',
  							`IN_Spec_Version_Major` int(11) NOT NULL DEFAULT '1',
  							`IN_Spec_Version_Minor` int(11) NOT NULL DEFAULT '1',
  							`TE_XML_Link` text NOT NULL,
 							`BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23";


$query_create_state_variable_tb = "CREATE TABLE IF NOT EXISTS `state_variable` (
					  `PK_Id` int(11) NOT NULL,
  					  `FK_Service` int(11) NOT NULL,
					  `TE_Name` varchar(32) NOT NULL,
					  `EN_Send_Events` enum('yes','no') NOT NULL DEFAULT 'yes' COMMENT 'a',
					  `EN_Multicast` enum('yes','no') NOT NULL DEFAULT 'no',
					  `EN_Data_Type` enum('ui1','ui2','ui4','i1','i2','i4','int','r4','r8','number','fixed.14.4','float','char','string','date','date time','time.tz','boolean','bin.base64','bin.hex','uri','uuid') NOT NULL,
					  `TE_Default_Value` text COMMENT 'RECOMMENDED. Expected, initial value. Defined by a UPnP Forum working committee or delegated to UPnP vendor. MUST match data type. MUST satisfy <allowedValueList> or <allowedValueRange> constraints. For a state variable using an extended data type via the type attribute and containing complex data, the <defaultValue> element MUST NOT be present.\n',
					  `EN_Reading_Circuit_Type` enum('DIGITAL','PWM','ANALOG') NOT NULL COMMENT 'What should be sent to Slave controller when this state variable changes\n',
					  `EN_Writing_Circuit_Type` enum('DIGITAL','PWM','ANALOG') NOT NULL,
					  `IN_Reading_Circuit_Pin` int(11) NOT NULL,
					  `IN_Writing_Circuit_Pin` int(11) NOT NULL,
					  `EN_Reading_Circuit_Baudrate` enum('0','300','600','1200','2400','4800','9600','14400','19200','28800','38400','57600','115200') NOT NULL,
					  `EN_Writing_Circuit_Baudrate` enum('0','300','600','1200','2400','4800','9600','14400','19200','28800','38400','57600','115200') NOT NULL,
					  `BO_Deleted` tinyint(4) NOT NULL
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;";


$query_create_users_tb = "CREATE TABLE IF NOT EXISTS `users` (
			 `PK_Id` int(11) NOT NULL,
			 `TE_Name` text,
			 `TE_Username` text NOT NULL,
			 `TE_Password` text NOT NULL
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";

$query_select_all_slaves = "SELECT PK_Unic_Name,TE_Type,TE_Address,TE_Description FROM slave_controler";


?>
