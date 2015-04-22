<?php

final
class ServiceMapper
{
    /**
     * @return ServiceModel[]
     */
    public static
    function get_all()
    {
        $service_array = [];
        $service_query = DatabaseAdapter::query("SELECT * FROM service WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($service_query)):
            $model = new ServiceModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_device($db_row["FK_Device"]);
            $model->set_friendly_name($db_row["TE_Friendly_Name"]);
            $model->set_description($db_row["TE_Description"]);
            $model->set_service_type($db_row["TE_Service_Type"]);
            $model->set_service_id($db_row["TE_Service_Id"]);
            $model->set_scpdurl($db_row["TE_SCPDURL"]);
            $model->set_control_url($db_row["TE_Control_URL"]);
            $model->set_event_suburl($db_row["TE_Event_SubURL"]);
            $model->set_spec_version_major($db_row["IN_Spec_Version_Major"]);
            $model->set_spec_version_minor($db_row["IN_Spec_Version_Minor"]);
            $model->set_xml_link($db_row["TE_XML_Link"]);
            $service_array[] = $model;

        endwhile;
        return $service_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $service_query = DatabaseAdapter::query("
												SELECT * FROM service
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $service_row = DatabaseAdapter::fetch_row($service_query);
        $model = new ServiceModel();
        $model->set_pk_id($service_row["PK_Id"]);
        $model->set_device($service_row["FK_Device"]);
        $model->set_friendly_name($service_row["TE_Friendly_Name"]);
        $model->set_description($service_row["TE_Description"]);
        $model->set_service_type($service_row["TE_Service_Type"]);
        $model->set_service_id($service_row["TE_Service_Id"]);
        $model->set_scpdurl($service_row["TE_SCPDURL"]);
        $model->set_control_url($service_row["TE_Control_URL"]);
        $model->set_event_suburl($service_row["TE_Event_SubURL"]);
        $model->set_spec_version_major($service_row["IN_Spec_Version_Major"]);
        $model->set_spec_version_minor($service_row["IN_Spec_Version_Minor"]);
        $model->set_xml_link($service_row["TE_XML_Link"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $service_array = [];
        $item_name = escape_text($item_name);
        $service_query = DatabaseAdapter::query("SELECT * FROM service WHERE TE_Friendly_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($service_query)):
            $model = new ServiceModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_device($db_row["FK_Device"]);
            $model->set_friendly_name($db_row["TE_Friendly_Name"]);
            $model->set_description($db_row["TE_Description"]);
            $model->set_service_type($db_row["TE_Service_Type"]);
            $model->set_service_id($db_row["TE_Service_Id"]);
            $model->set_scpdurl($db_row["TE_SCPDURL"]);
            $model->set_control_url($db_row["TE_Control_URL"]);
            $model->set_event_suburl($db_row["TE_Event_SubURL"]);
            $model->set_spec_version_major($db_row["IN_Spec_Version_Major"]);
            $model->set_spec_version_minor($db_row["IN_Spec_Version_Minor"]);
            $model->set_xml_link($db_row["TE_XML_Link"]);
            $service_array[] = $model;

        endwhile;
        return $service_array;

    }

    public static
    function save(ServiceModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO service
					(FK_Device,TE_Friendly_Name,TE_Description,TE_Service_Type,TE_Service_Id,TE_SCPDURL,TE_Control_URL,TE_Event_SubURL,IN_Spec_Version_Major,IN_Spec_Version_Minor,TE_XML_Link)
VALUES
						(
                    '{$model->get_device ()}','{$model->get_friendly_name ()}','{$model->get_description ()}','{$model->get_service_type ()}','{$model->get_service_id ()}','{$model->get_scpdurl ()}','{$model->get_control_url ()}','{$model->get_event_suburl ()}','{$model->get_spec_version_major ()}','{$model->get_spec_version_minor ()}','{$model->get_xml_link ()}')");
        $device_id = $model->get_device();
        $db_instance = DatabaseAdapter::get_connection();
        $service_id = $db_instance->lastInsertId();
        $service_friendly_name = str_replace(str_split('\\/:*?"<>|-_ '), "", ucwords(mb_strtolower(str_replace("_", " ", $model->get_friendly_name()))));
        $service_type = "urn:uiot-org:service:$service_friendly_name:$service_id";
        $serviceId = "urn:uiot-org:serviceId:$service_friendly_name:$service_id";
        $SCPD_URP = "../services/{$service_id}.xml";
        $xml = ROOT_PATH . "/etc/xml/services/{$service_id}/.xml";
        $control_url = "/device_{$device_id}/service_{$service_id}/control";
        $event_url = "/device_{$device_id}/service_{$service_id}/event";
        DatabaseAdapter::query("UPDATE Service SET TE_Service_Id='$serviceId', TE_Service_Type='$service_type', TE_XML_Link='$xml' ,TE_SCPDURL='$SCPD_URP', TE_Control_URL='$control_url',TE_Event_SubURL='$event_url' WHERE PK_Id=$service_id ");
        XmlHandler::build_device_xml($device_id);
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function update(ServiceModel $model, $service_id = null)
    {
        DatabaseAdapter::query("
					UPDATE service
					SET
						FK_Device = '{$model->get_device ()}',
						TE_Friendly_Name = '{$model->get_friendly_name ()}',
						TE_Description = '{$model->get_description ()}'
                     WHERE
						PK_Id     = '$service_id'
						AND
						BO_Deleted     = '0';"
        );
        $device_id = $model->get_device();
        $service_friendly_name = str_replace(str_split('\\/:*?"<>|-_ '), "", ucwords(mb_strtolower(str_replace("_", " ", $model->get_friendly_name()))));
        $service_type = "urn:uiot-org:service:$service_friendly_name:$service_id";
        $serviceId = "urn:uiot-org:serviceId:$service_friendly_name:$service_id";
        $SCPD_URP = "../services/{$service_id}.xml";
        $xml = ROOT_PATH . "/etc/xml/services/{$service_id}/.xml";
        $control_url = "/device_{$device_id}/service_{$service_id}/control";
        $event_url = "/device_{$device_id}/service_{$service_id}/event";
        DatabaseAdapter::query("UPDATE Service SET TE_Service_Id='$serviceId', TE_Service_Type='$service_type', TE_XML_Link='$xml' ,TE_SCPDURL='$SCPD_URP', TE_Control_URL='$control_url',TE_Event_SubURL='$event_url' WHERE PK_Id=$service_id ");
        XmlHandler::build_device_xml($device_id);
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function remove($service_id = null)
    {
        DatabaseAdapter::query("
					UPDATE service
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$service_id'");
        XmlHandler::build_device_xml($service_id);
    }

}
