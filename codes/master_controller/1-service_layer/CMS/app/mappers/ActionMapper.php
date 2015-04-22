<?php

final
class ActionMapper
{
    public static
    function get_all()
    {
        $action_array = [];
        $action_query = DatabaseAdapter::query("SELECT * FROM action WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($action_query)):
            $model = new ActionModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_service($db_row["FK_Service"]);
            $model->set_name($db_row["TE_Name"]);
            $action_array[] = $model;

        endwhile;
        return $action_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $action_query = DatabaseAdapter::query("
												SELECT * FROM action
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $action_row = DatabaseAdapter::fetch_row($action_query);
        $model = new ActionModel();
        $model->set_pk_id($action_row["PK_Id"]);
        $model->set_service($action_row["FK_Service"]);
        $model->set_name($action_row["TE_Name"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $action_array = [];
        $item_name = escape_text($item_name);
        $action_query = DatabaseAdapter::query("SELECT * FROM action WHERE TE_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($action_query)):
            $model = new ActionModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_service($db_row["FK_Service"]);
            $model->set_name($db_row["TE_Name"]);
            $action_array[] = $model;

        endwhile;
        return $action_array;

    }

    public static
    function save(ActionModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO action
					(PK_Id,FK_Service,TE_Name)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_service ()}','{$model->get_name ()}')");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function update(ActionModel $model, $action_id = null)
    {
        DatabaseAdapter::query("
					UPDATE action
					SET
						FK_Service = '{$model->get_service ()}',TE_Name = '{$model->get_name ()}'WHERE
						PK_Id   = '$action_id'
						AND
						BO_Deleted     = '0';");
        $service_id = $model->get_service();
        XmlHandler::build_service_xml($service_id);
    }

    public static
    function remove($action_id = null)
    {
        DatabaseAdapter::query("
					UPDATE action
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$action_id'");
        $query = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM action WHERE PK_Id='$action_id' AND BO_Deleted='0'"));
        DatabaseAdapter::query("UPDATE argument SET BO_Deleted='1' WHERE FK_Action='$action_id'");
        XmlHandler::build_service_xml($query["FK_Service"]);
    }

}
