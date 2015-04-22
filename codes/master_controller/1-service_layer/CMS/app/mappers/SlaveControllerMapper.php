<?php

final
class SlaveControllerMapper
{
    public static
    function get_all()
    {
        $slave_controller_array = [];
        $slave_controller_query = DatabaseAdapter::query("SELECT * FROM slave_controller WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($slave_controller_query)):
            $model = new SlaveControllerModel();
            $model->set_unic_name($db_row["PK_Unic_Name"]);
            $model->set_type($db_row["TE_Type"]);
            $model->set_address($db_row["TE_Address"]);
            $model->set_description($db_row["TE_Description"]);
            $slave_controller_array[] = $model;

        endwhile;
        return $slave_controller_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $slave_controller_query = DatabaseAdapter::query("
												SELECT * FROM slave_controller
												WHERE
													BO_Deleted = '0'
												AND
													PK_Unic_Name      = '$item_id'
												LIMIT 1");
        $slave_controller_row = DatabaseAdapter::fetch_row($slave_controller_query);
        $model = new SlaveControllerModel();
        $model->set_unic_name($slave_controller_row["PK_Unic_Name"]);
        $model->set_type($slave_controller_row["TE_Type"]);
        $model->set_address($slave_controller_row["TE_Address"]);
        $model->set_description($slave_controller_row["TE_Description"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $slave_controller_array = [];
        $item_name = escape_text($item_name);
        $slave_controller_query = DatabaseAdapter::query("SELECT * FROM slave_controller WHERE PK_Unic_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($slave_controller_query)):
            $model = new SlaveControllerModel();
            $model->set_unic_name($db_row["PK_Unic_Name"]);
            $model->set_type($db_row["TE_Type"]);
            $model->set_address($db_row["TE_Address"]);
            $model->set_description($db_row["TE_Description"]);
            $slave_controller_array[] = $model;

        endwhile;
        return $slave_controller_array;

    }

    public static
    function save(SlaveControllerModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO slave_controller
					(PK_Unic_Name,TE_Type,TE_Address,TE_Description)
VALUES
						(
'{$model->get_unic_name ()}','{$model->get_type ()}','{$model->get_address ()}','{$model->get_description ()}')");
    }

    public static
    function update(SlaveControllerModel $model, $slave_controller_id = null)
    {
        DatabaseAdapter::query("
					UPDATE slave_controller
					SET
PK_Unic_Name = '{$model->get_unic_name ()}',TE_Type = '{$model->get_type ()}',TE_Address = '{$model->get_address ()}',TE_Description = '{$model->get_description ()}'WHERE
						PK_Unic_Name   = '$slave_controller_id'
						AND
						BO_Deleted     = '0';");
    }

    public static
    function remove($slave_controller_id = null)
    {
        DatabaseAdapter::query("
					UPDATE slave_controller
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Unic_Name = '$slave_controller_id'");
        $query = DatabaseAdapter::query("SELECT PK_Id FROM device WHERE FK_Slave_Controller='$slave_controller_id' AND BO_Deleted='0'");
        while ($device = DatabaseAdapter::fetch_row($query)):
            $device_id = $device["PK_Id"];
            DatabaseAdapter::query("UPDATE device SET BO_Deleted='1' WHERE FK_Id='$device_id'");
            $query2 = DatabaseAdapter::query("SELECT PK_Id FROM service WHERE FK_Device='$device_id'");
            while ($count = DatabaseAdapter::fetch_row($query2)):
                $service_id = $count["PK_Id"];
                DatabaseAdapter::query("UPDATE service SET BO_Deleted='1' WHERE PK_Id='$service_id'");
                DatabaseAdapter::query("UPDATE state_variable SET BO_Deleted='1' WHERE FK_Service='$service_id'");
                DatabaseAdapter::query("UPDATE action SET BO_Deleted='1' WHERE FK_Service='$service_id'");
                @unlink(ROOT_PATH . "/etc/xml/services/" . $service_id . ".xml");
            endwhile;
            @unlink(ROOT_PATH . "/etc/xml/services/" . $device_id . ".xml");
        endwhile;
    }

}
