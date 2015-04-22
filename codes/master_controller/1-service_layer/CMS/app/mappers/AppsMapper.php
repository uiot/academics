<?php

final
class AppsMapper
{
    public static
    function get_all()
    {
        $apps_array = [];
        $apps_query = DatabaseAdapter::query("SELECT * FROM apps WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($apps_query)):
            $model = new AppsModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_public_name($db_row["TE_Public_Name"]);
            $model->set_version($db_row["TE_Version"]);
            $model->set_author($db_row["TE_Author"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_description($db_row["TE_Description"]);
            $model->set_device_id($db_row["TE_Device_Id"]);
            $model->set_store_id($db_row["TE_Store_Id"]);
            $apps_array[] = $model;

        endwhile;
        return $apps_array;

    }

    public static
    function check_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $apps_query = DatabaseAdapter::query("
												SELECT * FROM apps
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $model = DatabaseAdapter::num_rows($apps_query);
        return $model;

    }

    public static
    function check_by_name($item_id = null)
    {
        $item_id = escape_text($item_id);
        $apps_query = DatabaseAdapter::query("
												SELECT * FROM apps
												WHERE
													BO_Deleted = '0'
												AND
													TE_Name      = '$item_id'
												LIMIT 1");
        $model = DatabaseAdapter::num_rows($apps_query);
        return $model;
    }

    public static
    function check_by_store($item_id = null)
    {
        $item_id = escape_text($item_id);
        $apps_query = DatabaseAdapter::query("
												SELECT * FROM apps
												WHERE
													BO_Deleted = '0'
												AND
													TE_Store_Id      = '$item_id'
												LIMIT 1");
        $model = DatabaseAdapter::num_rows($apps_query);
        return $model;
    }

    public static
    function get_by_store($item_id = null)
    {
        $item_id = escape_text($item_id);
        $apps_query = DatabaseAdapter::query("
												SELECT * FROM apps
												WHERE
													BO_Deleted = '0'
												AND
													TE_Store_Id      = '$item_id'
												LIMIT 1");
        $apps_row = DatabaseAdapter::fetch_row($apps_query);
        $model = new AppsModel();
        $model->set_pk_id($apps_row["PK_Id"]);
        $model->set_public_name($apps_row["TE_Public_Name"]);
        $model->set_version($apps_row["TE_Version"]);
        $model->set_author($apps_row["TE_Author"]);
        $model->set_name($apps_row["TE_Name"]);
        $model->set_description($apps_row["TE_Description"]);
        $model->set_device_id($apps_row["TE_Device_Id"]);
        $model->set_store_id($apps_row["TE_Store_Id"]);
        return $model;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $apps_query = DatabaseAdapter::query("
												SELECT * FROM apps
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $apps_row = DatabaseAdapter::fetch_row($apps_query);
        $model = new AppsModel();
        $model->set_pk_id($apps_row["PK_Id"]);
        $model->set_public_name($apps_row["TE_Public_Name"]);
        $model->set_version($apps_row["TE_Version"]);
        $model->set_author($apps_row["TE_Author"]);
        $model->set_name($apps_row["TE_Name"]);
        $model->set_description($apps_row["TE_Description"]);
        $model->set_device_id($apps_row["TE_Device_Id"]);
        $model->set_store_id($apps_row["TE_Store_Id"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $apps_array = [];
        $item_name = escape_text($item_name);
        $apps_query = DatabaseAdapter::query("SELECT * FROM apps WHERE TE_Name LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($apps_query)):
            $model = new AppsModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_public_name($db_row["TE_Public_Name"]);
            $model->set_version($db_row["TE_Version"]);
            $model->set_author($db_row["TE_Author"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_description($db_row["TE_Description"]);
            $model->set_device_id($db_row["TE_Device_Id"]);
            $model->set_store_id($db_row["TE_Store_Id"]);
            $apps_array[] = $model;

        endwhile;
        return $apps_array;
    }

    public static
    function save(AppsModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO apps
					(PK_Id,TE_Public_Name,TE_Version,TE_Author,TE_Name,TE_Description,TE_Device_Id,TE_Store_id)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_public_name ()}','{$model->get_version ()}','{$model->get_author ()}','{$model->get_name ()}','{$model->get_description ()}','{$model->get_device_id ()}','{$model->get_store_id ()}')");
        $db = DatabaseAdapter::get_connection();
        return $db->lastInsertId();
    }

    public static
    function update(AppsModel $model, $apps_id = null)
    {
        DatabaseAdapter::query("
					UPDATE apps
					SET
						TE_Public_Name = '{$model->get_public_name ()}',TE_Version = '{$model->get_version ()}',TE_Author = '{$model->get_author ()}',TE_Name = '{$model->get_name ()}',TE_Description = '{$model->get_description ()}',TE_Device_Id = '{$model->get_device_id ()}',TE_Store_Id = '{$model->get_store_id ()}' WHERE
						PK_Id   = '$apps_id'
						AND
						BO_Deleted     = '0';");
    }

    public static
    function update_by_store_id(AppsModel $model, $apps_id = null)
    {
        DatabaseAdapter::query("
					UPDATE apps
					SET
						TE_Public_Name = '{$model->get_public_name ()}',TE_Version = '{$model->get_version ()}',TE_Author = '{$model->get_author ()}',TE_Name = '{$model->get_name ()}',TE_Description = '{$model->get_description ()}',TE_Device_Id = '{$model->get_device_id ()}',TE_Store_Id = '{$model->get_store_id ()}' WHERE
						TE_Store_Id   = '$apps_id'
						AND
						BO_Deleted     = '0';");
    }


    public static
    function remove($apps_id = null)
    {
        DatabaseAdapter::query("
					UPDATE apps
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$apps_id'");
    }

}
