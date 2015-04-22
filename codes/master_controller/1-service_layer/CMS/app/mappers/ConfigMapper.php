<?php

final
class ConfigMapper
{
    public static
    function get_all()
    {
        $db_row = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT * FROM config"));
        $model = new ConfigModel();
        $model->set_cml_ip($db_row["TE_CML_Ip"]);
        $model->set_cml_port($db_row["TE_CML_Port"]);
        $model->set_svl_ip($db_row["TE_SVL_Ip"]);
        $model->set_svl_port($db_row["TE_SVL_Port"]);
        $model->set_cll_ip($db_row["TE_CLL_Ip"]);
        $model->set_cll_port_in($db_row["TE_CLL_Port_In"]);
        $model->set_cll_port_out($db_row["TE_CLL_Port_Out"]);
        $model->set_max_cpu($db_row["TE_Max_Cpu"]);
        $model->set_max_mem($db_row["TE_Max_Mem"]);
        $config_array = $model;

        return $config_array;

    }

    public static
    function update(ConfigModel $model)
    {
        DatabaseAdapter::query("
					UPDATE config
					SET
TE_CML_Ip = '{$model->get_cml_ip ()}',TE_CML_Port = '{$model->get_cml_port ()}',TE_SVL_Ip = '{$model->get_svl_ip ()}',TE_SVL_Port = '{$model->get_svl_port ()}',TE_CLL_Ip = '{$model->get_cll_ip ()}',TE_CLL_Port_In = '{$model->get_cll_port_in ()}',TE_CLL_Port_Out = '{$model->get_cll_port_out ()}',TE_Max_Cpu = '{$model->get_max_cpu ()}',TE_Max_Mem = '{$model->get_max_mem ()}'");
    }
}
