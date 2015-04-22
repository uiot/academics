<?php

final
class AllowedValuesStringsMapper
{
    public static
    function get_all()
    {
        $allowed_values_strings_array = [];
        $allowed_values_strings_query = DatabaseAdapter::query("SELECT * FROM allowed_values_strings WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($allowed_values_strings_query)):
            $model = new AllowedValuesStringsModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_state_variable($db_row["FK_State_Variable"]);
            $model->set_value($db_row["TE_Value"]);
            $allowed_values_strings_array[] = $model;

        endwhile;
        return $allowed_values_strings_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $allowed_values_strings_query = DatabaseAdapter::query("
												SELECT * FROM allowed_values_strings
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $allowed_values_strings_row = DatabaseAdapter::fetch_row($allowed_values_strings_query);
        $model = new AllowedValuesStringsModel();
        $model->set_pk_id($allowed_values_strings_row["PK_Id"]);
        $model->set_state_variable($allowed_values_strings_row["FK_State_Variable"]);
        $model->set_value($allowed_values_strings_row["TE_Value"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $allowed_values_strings_array = [];
        $item_name = escape_text($item_name);
        $allowed_values_strings_query = DatabaseAdapter::query("SELECT * FROM allowed_values_strings WHERE TE_Value LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($allowed_values_strings_query)):
            $model = new AllowedValuesStringsModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_state_variable($db_row["FK_State_Variable"]);
            $model->set_value($db_row["TE_Value"]);
            $allowed_values_strings_array[] = $model;

        endwhile;
        return $allowed_values_strings_array;

    }

    public static
    function save(AllowedValuesStringsModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO allowed_values_strings
					(PK_Id,FK_State_Variable,TE_Value)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_state_variable ()}','{$model->get_value ()}')");
    }

    public static
    function update(AllowedValuesStringsModel $model, $allowed_values_strings_id = null)
    {
        DatabaseAdapter::query("
					UPDATE allowed_values_strings
					SET
						FK_State_Variable = '{$model->get_state_variable ()}',TE_Value = '{$model->get_value ()}'WHERE
						PK_Id   = '$allowed_values_strings_id'
						AND
						BO_Deleted     = '0';");
    }

    public static
    function remove($allowed_values_strings_id = null)
    {
        DatabaseAdapter::query("
					UPDATE allowed_values_strings
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$allowed_values_strings_id'");
    }

}
