<?php

/**
 * Class XML_Handler
 * Handles the XML Creation for UPnP
 */
final
class XmlHandler
{
    /**
     * @param $device_id
     */
    public static
    function build_device_xml($device_id = '')
    {
        @unlink(ROOT_PATH . "/etc/xml/devices/" . $device_id . ".xml");
        $device_row = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM device WHERE PK_Id='$device_id' AND BO_Deleted=0"));
        $udn = $device_row['TE_UDN'];
        $major = $device_row['IN_Spec_Version_Major'];
        $minor = $device_row['IN_Spec_Version_Minor'];
        $device_type = $device_row['TE_Device_Type'];
        $friendly_name = $device_row['TE_Friendly_Name'];
        $manufacturer = $device_row['TE_Manufacturer'];
        $manufacturer_url = $device_row['TE_Manufacturer_URL'];
        $model_description = $device_row['TE_Model_Description'];
        $model_name = $device_row['TE_Model_Name'];
        $model_version = $device_row['TE_Model_Number'];
        $model_url = $device_row['TE_Model_URL'];
        $serial_number = $device_row['TE_Serial_Number'];
        $upc_number = $device_row['TE_UPC'];
        $xml
            = "
<root xmlns='urn:schemas-upnp-org:device-1-0'>
     <specVersion>
         <major>{$major}</major>
         <minor>{$minor}</minor>
     </specVersion>
     <device>
         <deviceType>{$device_type}</deviceType>
         <friendlyName>{$friendly_name}</friendlyName>
         <manufacturer>{$manufacturer}</manufacturer>
         <manufacturerURL>{$manufacturer_url}</manufacturerURL>
         <modelDescription>{$model_description}</modelDescription>
         <modelName>{$model_name}</modelName>
         <modelNumber>{$model_version}</modelNumber>
         <modelURL>{$model_url}</modelURL>
         <serialNumber>{$serial_number}</serialNumber>
         <UDN>{$udn}</UDN>
         <UPC>{$upc_number}</UPC>
         <serviceList>";
        $service_query = DatabaseAdapter::query("SELECT * FROM service WHERE FK_Device='$device_id' AND BO_Deleted=0");
        while ($service_row = DatabaseAdapter::fetch_row($service_query)):
            $xml
                .= "
					 <service>
						 <serviceType>{$service_row['TE_Service_Type']}</serviceType>
						 <serviceId>{$service_row['TE_Service_Id']}</serviceId>
						 <SCPDURL>{$service_row['TE_SCPDURL']}</SCPDURL>
						 <controlURL>{$service_row['TE_Control_URL']}</controlURL>
						 <eventSubURL>{$service_row['TE_Event_SubURL']}</eventSubURL>
					 </service>";
        endwhile;
        $xml
            .= "
         </serviceList>
	</device>
</root>";
        $simple_xml = new SimpleXMLElement($xml);
        $simple_xml->asXML("../etc/xml/devices/" . $device_id . ".xml");
    }

    /**
     * @param $service_id
     */
    public static
    function build_service_xml($service_id)
    {
        @unlink(ROOT_PATH . "/etc/xml/services/" . $service_id . ".xml");
        $service_row = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM service WHERE PK_Id='$service_id'  AND BO_Deleted=0 "));
        $major = $service_row['IN_Spec_Version_Major'];
        $minor = $service_row['IN_Spec_Version_Minor'];
        $xml
            = "<scpd xmlns='urn:schemas-upnp-org:service-1-0'>
     <specVersion>
         <major>{$major}</major>
         <minor>{$minor}</minor>
     </specVersion>
     <actionList>";
        $action_query = DatabaseAdapter::query("SELECT * FROM action WHERE FK_Service='$service_id'  AND BO_Deleted=0 ");
        while ($action_row = DatabaseAdapter::fetch_row($action_query)):
            $action_id = $action_row['PK_Id'];
            $xml
                .= "
        <action>
		    <name>{$action_row['TE_Name']}</name>
     	    <argumentList>";
            $argument_query = DatabaseAdapter::query("SELECT * FROM Argument WHERE FK_Action='$action_id'  AND BO_Deleted=0 ORDER BY EN_Direction ASC");
            while ($argument_row = DatabaseAdapter::fetch_row($argument_query)):
                $service_var_id = $argument_row['FK_Related_State_Variable'];
                $direction = $argument_row['EN_Direction'];
                $state_variable = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM state_variable WHERE PK_Id='$service_var_id'  AND BO_Deleted=0"));
                $state_variable_name = $state_variable['TE_Name'];
                $argument_name = $argument_row['TE_Name'];
                $xml
                    .= "
				<argument>
					<name>{$argument_name}</name>
					<relatedStateVariable>{$state_variable_name}</relatedStateVariable>
					<direction>{$direction}</direction>
				</argument>";
            endwhile;
            $xml
                .= "
     	    </argumentList>
	    </action>
			";
        endwhile;
        $xml
            .= "
    </actionList>
    <serviceStateTable>";
        $state_variable_query = DatabaseAdapter::query("SELECT * FROM state_variable WHERE FK_Service='$service_id'  AND BO_Deleted=0");
        while ($state_variable_row = DatabaseAdapter::fetch_row($state_variable_query)):
            $xml
                .= "
        <stateVariable sendEvents='{$state_variable_row['EN_Send_Events']}'>
           <name>{$state_variable_row['TE_Name']}</name>
           <dataType>{$state_variable_row['EN_Data_Type']}</dataType>
           <defaultValue>{$state_variable_row['TE_Default_Value']}</defaultValue>
        </stateVariable>";
        endwhile;
        $xml
            .= "
    </serviceStateTable>
</scpd>";
        $simple_xml = new SimpleXMLElement($xml);
        $simple_xml->asXML(ROOT_PATH . "/etc/xml/services/" . $service_id . ".xml");
    }
}
