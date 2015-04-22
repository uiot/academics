<?php

final
class DeviceDownloader
{
    public $device;

    public
    function __construct($device = null)
    {
        $this->device = json_decode($device, true);
    }

    public
    function save_data()
    {
        $response = new stdClass();
        $response->status = "error";
        $response->message = "Invalid device.";
        try {
            if ($this->device != null) {
                $device_id = $this->save_device($this->device);

                $response->status = "success";
                $response->device_id = $device_id;
                $response->message = "Device was succesfully saved with online id $device_id.";
            }
        } catch (Exception $e) {
            $response->message = $e->getMessage();
        }
        return $response;
    }

    private
    function save_device($device = [])
    {
        //prepare data to be saved
        $TE_UDN = $device['TE_UDN'];
        $TE_Friendly_Name = $device['TE_Friendly_Name'];
        $TE_Device_Type = $device['TE_Device_Type'];
        $TE_Manufacturer = $device['TE_Manufacturer'];
        $TE_Manufacturer_URL = $device['TE_Manufacturer_URL'];
        $TE_Model_Description = $device['TE_Model_Description'];
        $TE_Model_Name = $device['TE_Model_Name'];
        $TE_Model_Number = $device['TE_Model_Number'];
        $TE_Model_URL = $device['TE_Model_URL'];
        $TE_Serial_Number = $device['TE_Serial_Number'];
        $TE_UPC = $device['TE_UPC'];
        $IN_Spec_Version_Major = $device['IN_Spec_Version_Major'];
        $IN_Spec_Version_Minor = $device['IN_Spec_Version_Minor'];
        $store_id = $device['store_id'];

        //save data
        $MyDB = DatabaseAdapter::get_connection();
        DatabaseAdapter::query("INSERT INTO device (TE_UDN, TE_Friendly_Name, TE_Device_Type,TE_Manufacturer, TE_Manufacturer_URL, TE_Model_Description,TE_Model_Name,TE_Model_Number, TE_Model_URL, TE_Serial_Number, TE_UPC,IN_Spec_Version_Major,IN_Spec_Version_Minor, TE_XML_Link, FK_Store_Id) VALUES ('$TE_UDN', '$TE_Friendly_Name', '$TE_Device_Type', '$TE_Manufacturer','$TE_Manufacturer_URL', '$TE_Model_Description', '$TE_Model_Name', '$TE_Model_Number','$TE_Model_URL', '$TE_Serial_Number', '$TE_UPC', '$IN_Spec_Version_Major', '$IN_Spec_Version_Minor','', '$store_id');");


        //save services
        $device_id = $MyDB->lastInsertId();

        $services_array = $device['services'];
        $this->save_services($device_id, $services_array);


        //return device id
        return $device_id;
    }


    /**
     * @param $device_id
     * @param $services_array
     */
    private
    function save_services($device_id = [], $services_array = [])
    {

        foreach ($services_array as $Key => $service) {
            //Receive json data
            $FK_Device = $device_id;
            $TE_Friendly_Name = $service['TE_Friendly_Name'];
            $TE_Description = $service['TE_Description'];
            $TE_Service_Type = $service['TE_Service_Type'];
            $TE_Service_Id = $service['TE_Service_Id'];
            $TE_SCPDURL = $service['TE_SCPDURL'];
            $TE_Control_URL = $service['TE_Control_URL'];
            $TE_Event_SubURL = $service['TE_Event_SubURL'];
            $IN_Spec_Version_Major = $service['IN_Spec_Version_Major'];
            $IN_Spec_Version_Minor = $service['IN_Spec_Version_Minor'];

            //save received data
            $MyDB = DatabaseAdapter::get_connection();
            DatabaseAdapter::query("INSERT INTO service (FK_Device,TE_Friendly_Name,TE_Description,TE_Service_Type,TE_Service_Id,IN_Spec_Version_Major,IN_Spec_Version_Minor,TE_Control_URL,TE_Event_SubURL,TE_SCPDURL) VALUES ('$FK_Device','$TE_Friendly_Name','$TE_Description','$TE_Service_Type','$TE_Service_Id','$IN_Spec_Version_Major','$IN_Spec_Version_Minor','$TE_Control_URL','$TE_Event_SubURL', '$TE_SCPDURL');");
            $service_id = $MyDB->lastInsertId();

            //insert actions and state variables

            if (is_array($service['state_variables']))
                $this->save_state_variables($service_id, $service['state_variables']);


            if (is_array($service['actions']))
                $this->save_actions($service_id, $service['actions']);


        }
    }

    /**
     * @param int $service_id
     * @param array $state_variables_array
     */
    private
    function save_state_variables($service_id = 0, $state_variables_array = [])
    {
        foreach ($state_variables_array as $key => $state_variable) {
            //Receive json data
            $FK_Service = $service_id;
            $TE_Name = $state_variable['TE_Name'];
            $EN_Send_Events = $state_variable['EN_Send_Events'];
            $EN_Multicast = $state_variable['EN_Multicast'];
            $EN_Data_Type = $state_variable['EN_Data_Type'];
            $TE_Default_Value = $state_variable['TE_Default_Value'];
            $EN_Reading_Circuit_Type = $state_variable['EN_Reading_Circuit_Type'];
            $EN_Writing_Circuit_Type = $state_variable['EN_Writing_Circuit_Type'];
            $IN_Reading_Circuit_Pin = $state_variable['IN_Reading_Circuit_Pin'];
            $IN_Writing_Circuit_Pin = $state_variable['IN_Writing_Circuit_Pin'];
            $EN_Reading_Circuit_Baudrate = $state_variable['EN_Reading_Circuit_Baudrate'];
            $EN_Writing_Circuit_Baudrate = $state_variable['EN_Writing_Circuit_Baudrate'];

            //save received data
            DatabaseAdapter::query("INSERT INTO state_variable (FK_Service, TE_Name, EN_Send_Events, EN_Multicast, EN_Data_Type,TE_Default_Value, EN_Reading_Circuit_Type, EN_Writing_Circuit_Type,IN_Reading_Circuit_Pin, IN_Writing_Circuit_Pin,EN_Reading_Circuit_Baudrate, EN_Writing_Circuit_Baudrate) VALUES ('$FK_Service','$TE_Name','$EN_Send_Events','$EN_Multicast','$EN_Data_Type','$TE_Default_Value','$EN_Reading_Circuit_Type','$EN_Writing_Circuit_Type','$IN_Reading_Circuit_Pin','$IN_Writing_Circuit_Pin','$EN_Reading_Circuit_Baudrate','$EN_Writing_Circuit_Baudrate')");
        }
    }

    /**
     * @param int $service_id
     * @param     $actions_array
     */
    private
    function save_actions($service_id = 0, $actions_array = [])
    {
        foreach ($actions_array as $key => $action) {
            //Receive json data
            $FK_Service = $service_id;
            $TE_Name = $action['TE_Name'];

            //save received data
            $MyDB = DatabaseAdapter::get_connection();
            DatabaseAdapter::query("INSERT INTO action (FK_Service, TE_Name) VALUES ('$FK_Service', '$TE_Name')");
            $action_id = $MyDB->lastInsertId();
            if (is_array($action['arguments']))
                $this->save_arguments($action_id, $action['arguments']);
        }
    }

    private
    function save_arguments($action_id = 0, $arguments_array = [])
    {
        foreach ($arguments_array as $key => $argument) {
            //Receive json data
            $FK_Action = $action_id;
            $TE_Name = $argument['TE_Name'];
            $EN_Direction = $argument['EN_Direction'];
            $TE_Ret_Val = $argument['TE_Ret_Val'];
            $FK_Related_State_Variable = $argument['FK_Related_State_Variable'];

            //save received data
            DatabaseAdapter::query("INSERT INTO argument (FK_Action,TE_Name,EN_Direction,TE_Ret_Val,FK_Related_State_Variable) VALUES ('$FK_Action','$TE_Name','$EN_Direction','$TE_Ret_Val','$FK_Related_State_Variable')");
        }
    }
}