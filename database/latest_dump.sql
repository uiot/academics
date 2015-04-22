-- phpMyAdmin SQL Dump
-- version 4.2.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 22, 2015 at 04:49 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `UIoT_Middleware`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE IF NOT EXISTS `action` (
`PK_Id` int(11) NOT NULL,
  `FK_Service` int(11) NOT NULL,
  `TE_Name` varchar(32) NOT NULL,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `allowed_values_numerical`
--

CREATE TABLE IF NOT EXISTS `allowed_values_numerical` (
`PK_Id` int(11) NOT NULL,
  `FK_State_Variable` int(11) NOT NULL,
  `FL_Minimum_Value` float NOT NULL COMMENT 'REQUIRED. Inclusive lower bound. Defined by a UPnP Forum working committee or delegated to UPnP vendor. Single numeric value. The value of the <minimum> element MUST be less than the value of the <maximum> element. If a working committee has assigned an explicit value for this element, then vendors MUST use that value. Otherwise, vendors MUST choose their own value, but always within the allowed range for the data type of this state variable. If the working committee defines an allowed range for this state variable, then the value MUST be within that allowed range as defined by the <step> value (See below).\n',
  `FL_Maximum_Value` float NOT NULL COMMENT 'REQUIRED. Inclusive upper bound. Defined by a UPnP Forum working committee or delegated to UPnP vendor. Single numeric value. The value of the <maximum> element MUST be greater than the value of the <minimum> element. If a working committee has assigned an explicit value for this element, then vendors MUST use that value. Otherwise, vendors MUST choose their own value, but always within the allowed range for the data type of this state variable. If the working committee defines an allowed range for this state variable, then the value MUST be within that allowed range as defined by the <step> value (See below).\n',
  `FL_Step` float NOT NULL COMMENT 'asd\n',
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `allowed_values_strings`
--

CREATE TABLE IF NOT EXISTS `allowed_values_strings` (
`PK_Id` int(11) NOT NULL,
  `FK_State_Variable` int(11) NOT NULL,
  `TE_Value` varchar(32) NOT NULL COMMENT 'REQUIRED. A legal value for a string variable. Defined by a UPnP Forum working committee for standard state variables; if the UPnP Forum working committee permits it, UPnP vendors MAY add vendor-specific allowed values to standard state variables. Specified by UPnP vendor for extensions. String. MUST be < 32 characters.\n',
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
`PK_Id` int(11) NOT NULL,
  `TE_Public_Name` varchar(255) NOT NULL,
  `TE_Version` varchar(255) NOT NULL,
  `TE_Author` varchar(255) NOT NULL,
  `TE_Name` varchar(255) NOT NULL,
  `TE_Description` varchar(255) NOT NULL,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0',
  `TE_Device_Id` int(11) NOT NULL,
  `TE_Store_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `argument`
--

CREATE TABLE IF NOT EXISTS `argument` (
`PK_Id` int(11) NOT NULL,
  `FK_Action` int(11) NOT NULL,
  `TE_Name` varchar(32) NOT NULL COMMENT 'REQUIRED. Name of formal parameter. The name SHOULD be chosen to reflect the semantic use of the argument. MUST NOT contain a hyphen character (“-”, 2D Hex in UTF-8). First character MUST be a USASCII letter (“A”-“Z”, “a”-“z”), USASCII digit (“0”-“9”), an underscore (“_”), or a non- experimental Unicode letter or digit greater than U+007F. Succeeding characters MUST be a USASCII letter (“A”-“Z”, “a”-“z”), USASCII digit (“0”-“9”), an underscore (“_”), a period (“.”), a Unicode combiningchar, an extender, or a non-experimental Unicode letter or digit greater than U+007F. The first three letters MUST NOT be “XML” in any combination of case. String. Case sensitive. SHOULD be < 32 characters.',
  `EN_Direction` enum('in','out') NOT NULL COMMENT 'REQUIRED. Defines whether argument is an input or output parameter.\nMUST be either “in” or “out” and not both. All input arguments MUST be listed before any output arguments.\n',
  `TE_Ret_Val` text COMMENT 'OPTIONAL. Identifies at most one output argument as the return value. If included, MUST be included as a subelement of the first output argument. (Element only; no value.)\n',
  `FK_Related_State_Variable` int(11) NOT NULL,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `TE_CML_Ip` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `TE_CML_Port` int(11) NOT NULL DEFAULT '12936',
  `TE_SVL_Ip` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `TE_SVL_Port` int(11) NOT NULL DEFAULT '12937',
  `TE_CLL_Ip` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `TE_CLL_Port_In` int(11) NOT NULL DEFAULT '12938',
  `TE_CLL_Port_Out` int(11) NOT NULL DEFAULT '12939',
  `TE_Max_Cpu` int(22) NOT NULL DEFAULT '200',
  `TE_Max_Mem` int(22) NOT NULL DEFAULT '200'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`TE_CML_Ip`, `TE_CML_Port`, `TE_SVL_Ip`, `TE_SVL_Port`, `TE_CLL_Ip`, `TE_CLL_Port_In`, `TE_CLL_Port_Out`, `TE_Max_Cpu`, `TE_Max_Mem`) VALUES
('127.0.0.1', 12936, '127.0.0.1', 8080, '127.0.0.1', 12937, 12938, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
`PK_Id` int(11) NOT NULL COMMENT 'REQUIRED. Unique Device Name. Universally-unique identifier for the device, whether root or embedded. MUST be the same over time for a specific device instance (i.e., MUST survive reboots). MUST match the field value of the NT header field in device discovery messages. MUST match the prefix of the USN header field in all discovery messages. (Section 1, “Discovery” explains the NT and USN header fields.) MUST begin with “uuid:” followed by a UUID suffix specified by a UPnP vendor. See section 1.1.4, “UUID format and RECOMMENDED generation algorithms” for the MANDATORY UUID format.\n',
  `TE_UDN` varchar(255) NOT NULL,
  `FK_Slave_Controller` varchar(255) NOT NULL,
  `TE_Friendly_Name` text NOT NULL COMMENT 'REQUIRED. Short description for end user. MAY be localized (see ACCEPT-LANGUAGE and CONTENT- LANGUAGE header fields). Specified by UPnP vendor. String. SHOULD be < 64 characters.\n',
  `TE_Device_Type` text NOT NULL COMMENT 'REQUIRED. UPnP device type. Single URI.\n	•	For standard devices defined by a UPnP Forum working committee, MUST begin with “urn:schemas- upnp-org:device:” followed by the standardized device type suffix, a colon, and an integer device version i.e. urn:schemas-upnp-org:device:deviceType:ver. The highest supported version of the device type MUST be specified. \n	•	For non-standard devices specified by UPnP vendors, MUST begin with “urn:”, followed by a Vendor Domain Name, followed by “:device:”, followed by a device type suffix, colon, and an integer version, i.e., “urn:domain-name:device:deviceType:ver”. Period characters in the Vendor Domain \n          \n44\nName MUST be replaced with hyphens in accordance with RFC 2141. The highest supported version of the device type MUST be specified.\nThe device type suffix defined by a UPnP Forum working committee or specified by a UPnP vendor MUST be <= 64 chars, not counting the version suffix and separating colon.',
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
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0',
  `FK_Store_Id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
`PK_Id` int(11) NOT NULL,
  `FK_Device` int(11) NOT NULL,
  `TE_Friendly_Name` text NOT NULL,
  `TE_Description` text NOT NULL,
  `TE_Service_Type` text NOT NULL,
  `TE_Service_Id` text NOT NULL,
  `TE_SCPDURL` text NOT NULL COMMENT '	.	REQUIRED. Directory where service description is located or URL for service description.  MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. Specified by UPnP vendor. Single URL. ',
  `TE_Control_URL` text NOT NULL COMMENT '	.	REQUIRED. URL for control (see section 3, “Control”). MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. Specified by UPnP vendor. Single URL. \n',
  `TE_Event_SubURL` text NOT NULL COMMENT '	.	REQUIRED. URL for eventing (see section 4, “Eventing”). MUST be relative to the URL at which the device description is located in accordance with section 5 of RFC 3986. MUST be unique within the device; any two services MUST NOT have the same URL for eventing. \n',
  `IN_Spec_Version_Major` int(11) NOT NULL DEFAULT '1',
  `IN_Spec_Version_Minor` int(11) NOT NULL DEFAULT '1',
  `TE_XML_Link` text NOT NULL,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `slave_controller`
--

CREATE TABLE IF NOT EXISTS `slave_controller` (
  `PK_Unic_Name` varchar(255) NOT NULL,
  `TE_Type` text NOT NULL,
  `TE_Address` text NOT NULL,
  `TE_Description` text,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `state_variable`
--

CREATE TABLE IF NOT EXISTS `state_variable` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`PK_Id` int(11) NOT NULL,
  `TE_Name` text,
  `TE_Username` text NOT NULL,
  `TE_Password` text NOT NULL,
  `BO_Deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`PK_Id`, `TE_Name`, `TE_Username`, `TE_Password`, `BO_Deleted`) VALUES
(1, 'Administrator Acount', 'uiot_admin', '4acb4bc224acbbe3c2bfdcaa39a4324e', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Action_Service` (`FK_Service`);

--
-- Indexes for table `allowed_values_numerical`
--
ALTER TABLE `allowed_values_numerical`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Allowed_Values_Numerical_State_Variable` (`FK_State_Variable`);

--
-- Indexes for table `allowed_values_strings`
--
ALTER TABLE `allowed_values_strings`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Allowed_Values_State_Variable` (`FK_State_Variable`);

--
-- Indexes for table `apps`
--
ALTER TABLE `apps`
 ADD PRIMARY KEY (`PK_Id`);

--
-- Indexes for table `argument`
--
ALTER TABLE `argument`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Argument_State_Variable` (`FK_Related_State_Variable`), ADD KEY `FK_Argument_Action` (`FK_Action`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Device_Slave_Controller` (`FK_Slave_Controller`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_Service_Device` (`FK_Device`);

--
-- Indexes for table `slave_controller`
--
ALTER TABLE `slave_controller`
 ADD PRIMARY KEY (`PK_Unic_Name`);

--
-- Indexes for table `state_variable`
--
ALTER TABLE `state_variable`
 ADD PRIMARY KEY (`PK_Id`), ADD KEY `FK_State_Variable_Service` (`FK_Service`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`PK_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `allowed_values_numerical`
--
ALTER TABLE `allowed_values_numerical`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `allowed_values_strings`
--
ALTER TABLE `allowed_values_strings`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apps`
--
ALTER TABLE `apps`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `argument`
--
ALTER TABLE `argument`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'REQUIRED. Unique Device Name. Universally-unique identifier for the device, whether root or embedded. MUST be the same over time for a specific device instance (i.e., MUST survive reboots). MUST match the field value of the NT header field in device discovery messages. MUST match the prefix of the USN header field in all discovery messages. (Section 1, “Discovery” explains the NT and USN header fields.) MUST begin with “uuid:” followed by a UUID suffix specified by a UPnP vendor. See section 1.1.4, “UUID format and RECOMMENDED generation algorithms” for the MANDATORY UUID format.\n';
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `state_variable`
--
ALTER TABLE `state_variable`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `PK_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `action`
--
ALTER TABLE `action`
ADD CONSTRAINT `FK_Action_Service` FOREIGN KEY (`FK_Service`) REFERENCES `service` (`PK_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `allowed_values_numerical`
--
ALTER TABLE `allowed_values_numerical`
ADD CONSTRAINT `FK_Allowed_Values_Numerical_State_Variable` FOREIGN KEY (`FK_State_Variable`) REFERENCES `state_variable` (`PK_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `allowed_values_strings`
--
ALTER TABLE `allowed_values_strings`
ADD CONSTRAINT `FK_Allowed_Values_State_Variable` FOREIGN KEY (`FK_State_Variable`) REFERENCES `state_variable` (`PK_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `argument`
--
ALTER TABLE `argument`
ADD CONSTRAINT `FK_Argument_Action` FOREIGN KEY (`FK_Action`) REFERENCES `action` (`PK_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `FK_Argument_State_Variable` FOREIGN KEY (`FK_Related_State_Variable`) REFERENCES `state_variable` (`PK_Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
