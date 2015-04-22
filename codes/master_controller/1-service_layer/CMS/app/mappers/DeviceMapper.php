<?php

final
class DeviceMapper
{
    public static
    function get_all()
    {
        $device_array = [];
        $device_query = DatabaseAdapter::query("SELECT * FROM device WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($device_query)):
            $model = new DeviceModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_udn($db_row["TE_UDN"]);
            $model->set_slave_controller($db_row["FK_Slave_Controller"]);
            $model->set_friendly_name($db_row["TE_Friendly_Name"]);
            $model->set_device_type($db_row["TE_Device_Type"]);
            $model->set_manufacturer($db_row["TE_Manufacturer"]);
            $model->set_manufacturer_url($db_row["TE_Manufacturer_URL"]);
            $model->set_model_description($db_row["TE_Model_Description"]);
            $model->set_model_name($db_row["TE_Model_Name"]);
            $model->set_model_number($db_row["TE_Model_Number"]);
            $model->set_model_url($db_row["TE_Model_URL"]);
            $model->set_serial_number($db_row["TE_Serial_Number"]);
            $model->set_upc($db_row["TE_UPC"]);
            $model->set_spec_version_major($db_row["IN_Spec_Version_Major"]);
            $model->set_spec_version_minor($db_row["IN_Spec_Version_Minor"]);
            $model->set_xml_link($db_row["TE_XML_Link"]);
            $model->set_store_id($db_row["FK_Store_Id"]);
            $device_array[] = $model;

        endwhile;
        return $device_array;

    }

    public static
    function get_by_store($item_id = null)
    {
        $item_id = escape_text($item_id);
        $device_array = [];
        $device_query = DatabaseAdapter::query("SELECT * FROM device WHERE BO_Deleted = '0' AND FK_Store_Id = '$item_id'");
        while ($db_row = DatabaseAdapter::fetch_row($device_query)):
            $model = new DeviceModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_udn($db_row["TE_UDN"]);
            $model->set_slave_controller($db_row["FK_Slave_Controller"]);
            $model->set_friendly_name($db_row["TE_Friendly_Name"]);
            $model->set_device_type($db_row["TE_Device_Type"]);
            $model->set_manufacturer($db_row["TE_Manufacturer"]);
            $model->set_manufacturer_url($db_row["TE_Manufacturer_URL"]);
            $model->set_model_description($db_row["TE_Model_Description"]);
            $model->set_model_name($db_row["TE_Model_Name"]);
            $model->set_model_number($db_row["TE_Model_Number"]);
            $model->set_model_url($db_row["TE_Model_URL"]);
            $model->set_serial_number($db_row["TE_Serial_Number"]);
            $model->set_upc($db_row["TE_UPC"]);
            $model->set_spec_version_major($db_row["IN_Spec_Version_Major"]);
            $model->set_spec_version_minor($db_row["IN_Spec_Version_Minor"]);
            $model->set_xml_link($db_row["TE_XML_Link"]);
            $model->set_store_id($db_row["FK_Store_Id"]);
            $device_array[] = $model;

        endwhile;
        return $device_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $device_query = DatabaseAdapter::query("
												SELECT * FROM device
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id = '$item_id'
												LIMIT 1");
        $device_row = DatabaseAdapter::fetch_row($device_query);
        $model = new DeviceModel();
        $model->set_pk_id($device_row["PK_Id"]);
        $model->set_udn($device_row["TE_UDN"]);
        $model->set_slave_controller($device_row["FK_Slave_Controller"]);
        $model->set_friendly_name($device_row["TE_Friendly_Name"]);
        $model->set_device_type($device_row["TE_Device_Type"]);
        $model->set_manufacturer($device_row["TE_Manufacturer"]);
        $model->set_manufacturer_url($device_row["TE_Manufacturer_URL"]);
        $model->set_model_description($device_row["TE_Model_Description"]);
        $model->set_model_name($device_row["TE_Model_Name"]);
        $model->set_model_number($device_row["TE_Model_Number"]);
        $model->set_model_url($device_row["TE_Model_URL"]);
        $model->set_serial_number($device_row["TE_Serial_Number"]);
        $model->set_upc($device_row["TE_UPC"]);
        $model->set_spec_version_major($device_row["IN_Spec_Version_Major"]);
        $model->set_spec_version_minor($device_row["IN_Spec_Version_Minor"]);
        $model->set_xml_link($device_row["TE_XML_Link"]);
        $model->set_store_id($device_row["FK_Store_Id"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $device_array = [];
        $item_name = escape_text($item_name);
        $device_query = DatabaseAdapter::query("SELECT * FROM device WHERE TE_Friendly_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($device_query)):
            $model = new DeviceModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_udn($db_row["TE_UDN"]);
            $model->set_slave_controller($db_row["FK_Slave_Controller"]);
            $model->set_friendly_name($db_row["TE_Friendly_Name"]);
            $model->set_device_type($db_row["TE_Device_Type"]);
            $model->set_manufacturer($db_row["TE_Manufacturer"]);
            $model->set_manufacturer_url($db_row["TE_Manufacturer_URL"]);
            $model->set_model_description($db_row["TE_Model_Description"]);
            $model->set_model_name($db_row["TE_Model_Name"]);
            $model->set_model_number($db_row["TE_Model_Number"]);
            $model->set_model_url($db_row["TE_Model_URL"]);
            $model->set_serial_number($db_row["TE_Serial_Number"]);
            $model->set_upc($db_row["TE_UPC"]);
            $model->set_spec_version_major($db_row["IN_Spec_Version_Major"]);
            $model->set_spec_version_minor($db_row["IN_Spec_Version_Minor"]);
            $model->set_xml_link($db_row["TE_XML_Link"]);
            $model->set_store_id($db_row["FK_Store_Id"]);
            $device_array[] = $model;

        endwhile;
        return $device_array;

    }

    public static
    function save(DeviceModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO device
					(PK_Id,TE_UDN,FK_Slave_Controller,TE_Friendly_Name,TE_Device_Type,TE_Manufacturer,TE_Manufacturer_URL,TE_Model_Description,TE_Model_Name,TE_Model_Number,TE_Model_URL,TE_Serial_Number,TE_UPC,IN_Spec_Version_Major,IN_Spec_Version_Minor,TE_XML_Link,FK_Store_Id)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_udn ()}','{$model->get_slave_controller ()}','{$model->get_friendly_name ()}','{$model->get_device_type ()}','{$model->get_manufacturer ()}','{$model->get_manufacturer_url ()}','{$model->get_model_description ()}','{$model->get_model_name ()}','{$model->get_model_number ()}','{$model->get_model_url ()}','{$model->get_serial_number ()}','{$model->get_upc ()}','{$model->get_spec_version_major ()}','{$model->get_spec_version_minor ()}','{$model->get_xml_link ()}','{$model->get_store_id ()}')");
        $db_instance = DatabaseAdapter::get_connection();
        $device_id = $db_instance->lastInsertId();
        $xml = ROOT_PATH . "/etc/xml/devices/{$device_id}.xml";
        $udn = uuid_format($device_id);
        $device_name = str_replace(" ", "", ucwords(mb_strtolower($model->get_friendly_name())));
        $device_type = "urn:uiot-org:device:$device_name:$device_id";
        $minor_version = 1;
        $major_version = 1;
        DatabaseAdapter::query("UPDATE Device SET TE_UDN ='$udn', IN_Spec_Version_Major='$major_version', IN_Spec_Version_Minor='$minor_version', TE_XML_Link='$xml', TE_Device_Type='$device_type' WHERE PK_Id=$device_id ");
        XmlHandler::build_device_xml($device_id);
    }

    public static
    function update(DeviceModel $model, $device_id = null)
    {
        DatabaseAdapter::query("
					UPDATE device
					SET
						TE_UDN = '{$model->get_udn ()}',
						FK_Slave_Controller = '{$model->get_slave_controller ()}',
						TE_Friendly_Name = '{$model->get_friendly_name ()}',
						TE_Device_Type = '{$model->get_device_type ()}',
						TE_Manufacturer = '{$model->get_manufacturer ()}',
						TE_Manufacturer_URL = '{$model->get_manufacturer_url ()}',
						TE_Model_Description = '{$model->get_model_description ()}',
						TE_Model_Name = '{$model->get_model_name ()}',
						TE_Model_Number = '{$model->get_model_number ()}',
						TE_Model_URL = '{$model->get_model_url ()}',
						TE_Serial_Number = '{$model->get_serial_number ()}',
						TE_UPC = '{$model->get_upc ()}'
                    WHERE
						PK_Id   = '$device_id'
					AND
						BO_Deleted     = '0';");
        $xml = ROOT_PATH . "/etc/xml/devices/{$device_id}.xml";
        $udn = uuid_format($device_id);
        $device_name = str_replace(" ", "", ucwords(mb_strtolower($model->get_friendly_name())));
        $device_type = "urn:uiot-org:device:$device_name:$device_id";
        $minor_version = 1;
        $major_version = 1;
        DatabaseAdapter::query("UPDATE Device SET TE_UDN ='$udn', IN_Spec_Version_Major='$major_version', IN_Spec_Version_Minor='$minor_version', TE_XML_Link='$xml', TE_Device_Type='$device_type' WHERE PK_Id=$device_id ");
        XmlHandler::build_device_xml($device_id);
    }

    public static
    function update_slave($slave_id, $device_id = null)
    {
        $slave_id = escape_text($slave_id);
        $device_ids = escape_text($device_id);
        DatabaseAdapter::query("UPDATE device SET FK_Slave_Controller = '$slave_id' WHERE PK_Id = '$device_ids'");
        XmlHandler::build_device_xml($device_id);
    }

    public static
    function remove($device_id = null)
    {
        DatabaseAdapter::query("
					UPDATE device
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$device_id'");
        $service_query = DatabaseAdapter::query("SELECT PK_Id FROM service WHERE FK_Device=$device_id AND BO_Deleted='0'");
        while ($service_row = DatabaseAdapter::fetch_row($service_query)) {
            $service_id = $service_row["PK_Id"];
            DatabaseAdapter::query("UPDATE service SET BO_Deleted='1' WHERE PK_Id=$service_id");
            DatabaseAdapter::query("UPDATE state_variable SET BO_Deleted='1' WHERE FK_Service=$service_id");
            DatabaseAdapter::query("UPDATE action SET BO_Deleted='1' WHERE FK_Service=$service_id");
            unlink(ROOT_PATH . "/etc/xml/services/" . $service_id . ".xml");
        }
        unlink(ROOT_PATH . "/etc/xml/services/" . $device_id . ".xml");
    }

}
