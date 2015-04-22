<?php

/**
 * Class Device
 * Get Device Content
 */
final
class Device
{
    /**
     * @var stdClass
     */
    private $device;

    /**
     * @param int $device_id
     */
    public
    function __construct($device_id = 0)
    {
        $this->device = new stdClass();
        $device_row = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM device WHERE PK_Id = '$device_id'"));
        $this->device->TE_UDN = $device_row['TE_UDN'];
        $this->device->TE_Friendly_Name = $device_row['TE_Friendly_Name'];
        $this->device->TE_Device_Type = $device_row['TE_Device_Type'];
        $this->device->TE_Manufacturer = $device_row['TE_Manufacturer'];
        $this->device->TE_Manufacturer_URL = $device_row['TE_Manufacturer_URL'];
        $this->device->TE_Model_Description = $device_row['TE_Model_Description'];
        $this->device->TE_Model_Name = $device_row['TE_Model_Name'];
        $this->device->TE_Model_Number = $device_row['TE_Model_Number'];
        $this->device->TE_Model_URL = $device_row['TE_Model_URL'];
        $this->device->TE_Serial_Number = $device_row['TE_Serial_Number'];
        $this->device->TE_UPC = $device_row['TE_UPC'];
        $this->device->IN_Spec_Version_Major = $device_row['IN_Spec_Version_Major'];
        $this->device->IN_Spec_Version_Minor = $device_row['IN_Spec_Version_Minor'];
        $this->device->TE_XML_Link = $device_row['TE_XML_Link'];
        $this->device->services = $this->services($device_row["PK_Id"]);
    }


    /**
     * @param int $device_id
     *
     * @return array
     */
    private
    function services($device_id = 0)
    {
        $services = [];
        $services_query = DatabaseAdapter::query("SELECT * FROM service WHERE FK_Device='{$device_id}' AND BO_Deleted='0'");
        while ($db_service = DatabaseAdapter::fetch_row($services_query)):
            $service = new stdClass();
            $service->FK_Device = $db_service['FK_Device'];
            $service->TE_Friendly_Name = $db_service['TE_Friendly_Name'];
            $service->TE_Description = $db_service['TE_Description'];
            $service->TE_Service_Type = $db_service['TE_Service_Type'];
            $service->TE_Service_Id = $db_service['TE_Service_Id'];
            $service->TE_SCPDURL = $db_service['TE_SCPDURL'];
            $service->TE_Control_URL = $db_service['TE_Control_URL'];
            $service->TE_Event_SubURL = $db_service['TE_Event_SubURL'];
            $service->IN_Spec_Version_Major = $db_service['IN_Spec_Version_Major'];
            $service->IN_Spec_Version_Minor = $db_service['IN_Spec_Version_Minor'];
            $service->TE_XML_Link = $db_service['TE_XML_Link'];
            $service->actions = $this->get_actions($db_service['PK_Id']);
            $service->state_variables = $this->get_state_variables($db_service['PK_Id']);
            $services[] = $service;
        endwhile;
        return $services;
    }

    /**
     * @param int $service_id
     *
     * @return array
     */
    private
    function get_actions($service_id = 0)
    {
        $actions = [];
        $actions_query = DatabaseAdapter::query("SELECT * FROM action WHERE FK_Service='{$service_id}' AND BO_Deleted='0'");
        while ($db_action = DatabaseAdapter::fetch_row($actions_query)):
            $action = new stdClass();
            $action->FK_Service = $db_action['FK_Service'];
            $action->TE_Name = $db_action['TE_Name'];
            $this->get_arguments($db_action['PK_Id']);
            $actions[] = $action;
        endwhile;
        return $actions;
    }

    /**
     * @param int $action_id
     *
     * @return array
     */
    private
    function get_arguments($action_id = 0)
    {
        $arguments = [];
        $argument_query = DatabaseAdapter::query("SELECT * FROM argument WHERE FK_Action='{$action_id}' AND BO_Deleted='0'");
        while ($row = DatabaseAdapter::fetch_row($argument_query)):
            $argument = new stdClass();
            $argument->FK_Action = $row['FK_Action'];
            $argument->TE_Name = $row['TE_Name'];
            $argument->EN_Direction = $row['EN_Direction'];
            $argument->TE_Ret_Val = $row['TE_Ret_Val'];
            $argument->FK_Related_State_Variable = $row['FK_Related_State_Variable'];
            $arguments[] = $argument;
        endwhile;
        return $arguments;
    }

    /**
     * @param int $service_id
     *
     * @return array
     */
    private
    function get_state_variables($service_id = 0)
    {
        $state_variables = [];
        $state_variable_query = DatabaseAdapter::query("SELECT * FROM state_variable WHERE FK_Service = '$service_id' AND BO_Deleted='0'");
        while ($db_state_variable = DatabaseAdapter::fetch_row($state_variable_query)):
            $state_variable = new stdClass();
            $state_variable->FK_Service = $db_state_variable['FK_Service'];
            $state_variable->EN_Send_Events = $db_state_variable['EN_Send_Events'];
            $state_variable->EN_Multicast = $db_state_variable['EN_Multicast'];
            $state_variable->EN_Data_Type = $db_state_variable['EN_Data_Type'];
            $state_variable->TE_Default_Value = $db_state_variable['TE_Default_Value'];
            $state_variable->EN_Reading_Circuit_Type = $db_state_variable['EN_Reading_Circuit_Type'];
            $state_variable->EN_Writing_Circuit_Type = $db_state_variable['EN_Writing_Circuit_Type'];
            $state_variable->IN_Reading_Circuit_Pin = $db_state_variable['IN_Reading_Circuit_Pin'];
            $state_variable->IN_Writing_Circuit_Pin = $db_state_variable['IN_Writing_Circuit_Pin'];
            $state_variable->EN_Reading_Circuit_Baudrate = $db_state_variable['EN_Reading_Circuit_Baudrate'];
            $state_variable->EN_Writing_Circuit_Baudrate = $db_state_variable['EN_Writing_Circuit_Baudrate'];
            $state_variables[] = $state_variable;
        endwhile;
        return $state_variables;
    }

    /**
     * @return string
     */
    public
    function get_device_json()
    {
        return json_encode($this->device);
    }
}
