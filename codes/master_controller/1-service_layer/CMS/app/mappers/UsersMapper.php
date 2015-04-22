<?php

final
class UsersMapper
{
    public static
    function get_all()
    {
        $users_array = [];
        $users_query = DatabaseAdapter::query("SELECT * FROM users WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($users_query)):
            $model = new UsersModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_username($db_row["TE_Username"]);
            $model->set_password($db_row["TE_Password"]);
            $users_array[] = $model;

        endwhile;
        return $users_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $users_query = DatabaseAdapter::query("
												SELECT * FROM users
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $users_row = DatabaseAdapter::fetch_row($users_query);
        $model = new UsersModel();
        $model->set_pk_id($users_row["PK_Id"]);
        $model->set_name($users_row["TE_Name"]);
        $model->set_username($users_row["TE_Username"]);
        $model->set_password($users_row["TE_Password"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $users_array = [];
        $item_name = escape_text($item_name);
        $users_query = DatabaseAdapter::query("SELECT * FROM users WHERE TE_Username LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($users_query)):
            $model = new UsersModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_name($db_row["TE_Name"]);
            $model->set_username($db_row["TE_Username"]);
            $model->set_password($db_row["TE_Password"]);
            $users_array[] = $model;

        endwhile;
        return $users_array;

    }

    public static
    function save(UsersModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO users
					(PK_Id,TE_Name,TE_Username,TE_Password)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_name ()}','{$model->get_username ()}','{$model->get_password ()}')");
    }

    public static
    function update(UsersModel $model, $users_id = null)
    {
        DatabaseAdapter::query("
					UPDATE users
					SET
						TE_Name = '{$model->get_name ()}',TE_Username = '{$model->get_username ()}',TE_Password = '{$model->get_password ()}'WHERE
						PK_Id   = '$users_id'
						AND
						BO_Deleted     = '0';");
    }

    public static
    function remove($users_id = null)
    {
        DatabaseAdapter::query("
					UPDATE users
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$users_id'");
    }

    public static
    function authenticate($username = '', $password = '')
    {
        if (DatabaseAdapter::num_rows(DatabaseAdapter::query("SELECT 1 FROM users WHERE TE_Username = '$username' AND BO_Deleted = '0'")) == 1)
            return DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM users WHERE TE_Username = '$username' AND TE_Password = MD5('$password')"));
        else
            return 'username';
    }
}
