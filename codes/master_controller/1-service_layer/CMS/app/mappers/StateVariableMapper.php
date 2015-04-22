<?php

final
class StateVariableMapper
{
    public static
    function get_all()
    {
        $state_variable_array = [];
        $state_variable_query = DatabaseAdapter::query("SELECT * FROM state_variable WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($state_variable_query)):
            $model = new StateVariableModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_service($db_row["FK_Service"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_send_events($db_row["EN_Send_Events"]);
            $model->set_multicast($db_row["EN_Multicast"]);
            $model->set_data_type($db_row["EN_Data_Type"]);
            $model->set_default_value($db_row["TE_Default_Value"]);
            $model->set_reading_circuit_type($db_row["EN_Reading_Circuit_Type"]);
            $model->set_writing_circuit_type($db_row["EN_Writing_Circuit_Type"]);
            $model->set_reading_circuit_pin($db_row["IN_Reading_Circuit_Pin"]);
            $model->set_writing_circuit_pin($db_row["IN_Writing_Circuit_Pin"]);
            $model->set_reading_circuit_baudrate($db_row["EN_Reading_Circuit_Baudrate"]);
            $model->set_writing_circuit_baudrate($db_row["EN_Writing_Circuit_Baudrate"]);
            $state_variable_array[] = $model;

        endwhile;
        return $state_variable_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $state_variable_query = DatabaseAdapter::query("
												SELECT * FROM state_variable
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $state_variable_row = DatabaseAdapter::fetch_row($state_variable_query);
        $model = new StateVariableModel();
        $model->set_pk_id($state_variable_row["PK_Id"]);
        $model->set_service($state_variable_row["FK_Service"]);
        $model->set_name($state_variable_row["TE_Name"]);
        $model->set_send_events($state_variable_row["EN_Send_Events"]);
        $model->set_multicast($state_variable_row["EN_Multicast"]);
        $model->set_data_type($state_variable_row["EN_Data_Type"]);
        $model->set_default_value($state_variable_row["TE_Default_Value"]);
        $model->set_reading_circuit_type($state_variable_row["EN_Reading_Circuit_Type"]);
        $model->set_writing_circuit_type($state_variable_row["EN_Writing_Circuit_Type"]);
        $model->set_reading_circuit_pin($state_variable_row["IN_Reading_Circuit_Pin"]);
        $model->set_writing_circuit_pin($state_variable_row["IN_Writing_Circuit_Pin"]);
        $model->set_reading_circuit_baudrate($state_variable_row["EN_Reading_Circuit_Baudrate"]);
        $model->set_writing_circuit_baudrate($state_variable_row["EN_Writing_Circuit_Baudrate"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $state_variable_array = [];
        $item_name = escape_text($item_name);
        $state_variable_query = DatabaseAdapter::query("SELECT * FROM state_variable WHERE TE_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($state_variable_query)):
            $model = new StateVariableModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_service($db_row["FK_Service"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_send_events($db_row["EN_Send_Events"]);
            $model->set_multicast($db_row["EN_Multicast"]);
            $model->set_data_type($db_row["EN_Data_Type"]);
            $model->set_default_value($db_row["TE_Default_Value"]);
            $model->set_reading_circuit_type($db_row["EN_Reading_Circuit_Type"]);
            $model->set_writing_circuit_type($db_row["EN_Writing_Circuit_Type"]);
            $model->set_reading_circuit_pin($db_row["IN_Reading_Circuit_Pin"]);
            $model->set_writing_circuit_pin($db_row["IN_Writing_Circuit_Pin"]);
            $model->set_reading_circuit_baudrate($db_row["EN_Reading_Circuit_Baudrate"]);
            $model->set_writing_circuit_baudrate($db_row["EN_Writing_Circuit_Baudrate"]);
            $state_variable_array[] = $model;

        endwhile;
        return $state_variable_array;

    }

    public static
    function save(StateVariableModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO state_variable
					(PK_Id,FK_Service,TE_Name,EN_Send_Events,EN_Multicast,EN_Data_Type,TE_Default_Value,EN_Reading_Circuit_Type,EN_Writing_Circuit_Type,IN_Reading_Circuit_Pin,IN_Writing_Circuit_Pin,EN_Reading_Circuit_Baudrate,EN_Writing_Circuit_Baudrate)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_service ()}','{$model->get_name ()}','{$model->get_send_events ()}','{$model->get_multicast ()}','{$model->get_data_type ()}','{$model->get_default_value ()}','{$model->get_reading_circuit_type ()}','{$model->get_writing_circuit_type ()}','{$model->get_reading_circuit_pin ()}','{$model->get_writing_circuit_pin ()}','{$model->get_reading_circuit_baudrate ()}','{$model->get_writing_circuit_baudrate ()}')");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function update(StateVariableModel $model, $state_variable_id = null)
    {
        DatabaseAdapter::query("
					UPDATE state_variable
					SET
						FK_Service = '{$model->get_service ()}',TE_Name = '{$model->get_name ()}',EN_Send_Events = '{$model->get_send_events ()}',EN_Multicast = '{$model->get_multicast ()}',EN_Data_Type = '{$model->get_data_type ()}',TE_Default_Value = '{$model->get_default_value ()}',EN_Reading_Circuit_Type = '{$model->get_reading_circuit_type ()}',EN_Writing_Circuit_Type = '{$model->get_writing_circuit_type ()}',IN_Reading_Circuit_Pin = '{$model->get_reading_circuit_pin ()}',IN_Writing_Circuit_Pin = '{$model->get_writing_circuit_pin ()}',EN_Reading_Circuit_Baudrate = '{$model->get_reading_circuit_baudrate ()}',EN_Writing_Circuit_Baudrate = '{$model->get_writing_circuit_baudrate ()}'WHERE
						PK_Id   = '$state_variable_id'
						AND
						BO_Deleted     = '0';");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function remove($state_variable_id = null)
    {
        DatabaseAdapter::query("
					UPDATE state_variable
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$state_variable_id'");
        $query = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT FK_Service FROM state_variable  WHERE  PK_Id = $state_variable_id AND BO_Deleted='0'"));
        DatabaseAdapter::query("UPDATE argument SET BO_Deleted='1' WHERE FK_Related_State_Variable='$state_variable_id'");
        XmlHandler::build_service_xml($query["FK_Service"]);
    }

}
