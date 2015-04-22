<?php

final
class ArgumentMapper
{
    public static
    function get_all()
    {
        $argument_array = [];
        $argument_query = DatabaseAdapter::query("SELECT * FROM argument WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($argument_query)):
            $model = new ArgumentModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_action($db_row["FK_Action"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_direction($db_row["EN_Direction"]);
            $model->set_ret_val($db_row["TE_Ret_Val"]);
            $model->set_related_state_variable($db_row["FK_Related_State_Variable"]);
            $argument_array[] = $model;

        endwhile;
        return $argument_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $argument_query = DatabaseAdapter::query("
												SELECT * FROM argument
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $argument_row = DatabaseAdapter::fetch_row($argument_query);
        $model = new ArgumentModel();
        $model->set_pk_id($argument_row["PK_Id"]);
        $model->set_action($argument_row["FK_Action"]);
        $model->set_name($argument_row["TE_Name"]);
        $model->set_direction($argument_row["EN_Direction"]);
        $model->set_ret_val($argument_row["TE_Ret_Val"]);
        $model->set_related_state_variable($argument_row["FK_Related_State_Variable"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $argument_array = [];
        $item_name = escape_text($item_name);
        $argument_query = DatabaseAdapter::query("SELECT * FROM argument WHERE TE_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($argument_query)):
            $model = new ArgumentModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_action($db_row["FK_Action"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_direction($db_row["EN_Direction"]);
            $model->set_ret_val($db_row["TE_Ret_Val"]);
            $model->set_related_state_variable($db_row["FK_Related_State_Variable"]);
            $argument_array[] = $model;

        endwhile;
        return $argument_array;

    }

    public static
    function save(ArgumentModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO argument
					(FK_Action,TE_Name,EN_Direction,TE_Ret_Val,FK_Related_State_Variable)
VALUES
						(
'{$model->get_action ()}','{$model->get_name ()}','{$model->get_direction ()}','{$model->get_ret_val ()}','{$model->get_related_state_variable ()}')");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function update(ArgumentModel $model, $argument_id = null)
    {
        DatabaseAdapter::query("
					UPDATE argument
					SET
						FK_Action = '{$model->get_action ()}',TE_Name = '{$model->get_name ()}',EN_Direction = '{$model->get_direction ()}',TE_Ret_Val = '{$model->get_ret_val ()}',FK_Related_State_Variable = '{$model->get_related_state_variable ()}'WHERE
						PK_Id   = '$argument_id'
						AND
						BO_Deleted     = '0';");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function remove($argument_id = null)
    {
        DatabaseAdapter::query("
					UPDATE argument
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$argument_id'");
        $query = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM action WHERE PK_Id IN (SELECT FK_Action FROM Argument WHERE PK_Id=$argument_id AND BO_Deleted=0)"));
        XmlHandler::build_service_xml($query["FK_Service"]);
    }

}
