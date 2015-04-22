<?php

final
class AllowedValuesNumericalMapper
{
    public static
    function get_all()
    {
        $allowed_values_numerical_array = [];
        $allowed_values_numerical_query = DatabaseAdapter::query("SELECT * FROM allowed_values_numerical WHERE BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($allowed_values_numerical_query)):
            $model = new AllowedValuesNumericalModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_state_variable($db_row["FK_State_Variable"]);
            $model->set_minimum_value($db_row["FL_Minimum_Value"]);
            $model->set_maximum_value($db_row["FL_Maximum_Value"]);
            $model->set_step($db_row["FL_Step"]);
            $allowed_values_numerical_array[] = $model;

        endwhile;
        return $allowed_values_numerical_array;

    }

    public static
    function get_by_id($item_id = null)
    {
        $item_id = escape_text($item_id);
        $allowed_values_numerical_query = DatabaseAdapter::query("
												SELECT * FROM allowed_values_numerical
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '$item_id'
												LIMIT 1");
        $allowed_values_numerical_row = DatabaseAdapter::fetch_row($allowed_values_numerical_query);
        $model = new AllowedValuesNumericalModel();
        $model->set_pk_id($allowed_values_numerical_row["PK_Id"]);
        $model->set_state_variable($allowed_values_numerical_row["FK_State_Variable"]);
        $model->set_minimum_value($allowed_values_numerical_row["FL_Minimum_Value"]);
        $model->set_maximum_value($allowed_values_numerical_row["FL_Maximum_Value"]);
        $model->set_step($allowed_values_numerical_row["FL_Step"]);
        return $model;

    }

    public static
    function get_by_name($item_name = null)
    {
        $allowed_values_numerical_array = [];
        $item_name = escape_text($item_name);
        $allowed_values_numerical_query = DatabaseAdapter::query("SELECT * FROM allowed_values_numerical WHERE TE_Value LIKE '%$item_name%' AND BO_Deleted = '0' ");
        while ($db_row = DatabaseAdapter::fetch_row($allowed_values_numerical_query)):
            $model = new AllowedValuesNumericalModel();
            $model->set_pk_id($db_row["PK_Id"]);
            $model->set_state_variable($db_row["FK_State_Variable"]);
            $model->set_minimum_value($db_row["FL_Minimum_Value"]);
            $model->set_maximum_value($db_row["FL_Maximum_Value"]);
            $model->set_step($db_row["FL_Step"]);
            $allowed_values_numerical_array[] = $model;

        endwhile;
        return $allowed_values_numerical_array;

    }

    public static
    function save(AllowedValuesNumericalModel $model)
    {
        DatabaseAdapter::query("
					INSERT INTO allowed_values_numerical
					(PK_Id,FK_State_Variable,FL_Minimum_Value,FL_Maximum_Value,FL_Step)
VALUES
						(
'{$model->get_pk_id ()}','{$model->get_state_variable ()}','{$model->get_minimum_value ()}','{$model->get_maximum_value ()}','{$model->get_step ()}')");
    }

    public static
    function update(AllowedValuesNumericalModel $model, $allowed_values_numerical_id = null)
    {
        DatabaseAdapter::query("
					UPDATE allowed_values_numerical
					SET
						FK_State_Variable = '{$model->get_state_variable ()}',FL_Minimum_Value = '{$model->get_minimum_value ()}',FL_Maximum_Value = '{$model->get_maximum_value ()}',FL_Step = '{$model->get_step ()}'WHERE
						PK_Id   = '$allowed_values_numerical_id'
						AND
						BO_Deleted     = '0';");
    }

    public static
    function remove($allowed_values_numerical_id = null)
    {
        DatabaseAdapter::query("
					UPDATE allowed_values_numerical
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '$allowed_values_numerical_id'");
    }

}
