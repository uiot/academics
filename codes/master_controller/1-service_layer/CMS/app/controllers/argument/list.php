<?php

echo '<h2 > List Arguments </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "name" => "Argument", "action" => "Action", "svar" => "State Variable", "service" => "Service", "device" => "Device", "slave_controller" => "Slave Controller", "remove" => "Remove"];
$arguments_object = ArgumentMapper::get_all();

/* @var $arguments_object ArgumentsModel[] */
foreach ($arguments_object as $argument):

    $state_variable_mapper = StateVariableMapper::get_by_id($argument->get_related_state_variable());
    $action_mapper = ActionMapper::get_by_id($argument->get_action());
    $service_mapper = ServiceMapper::get_by_id($action_mapper->get_service());
    $device_mapper = DeviceMapper::get_by_id($service_mapper->get_device());

    $local_table_row = [
        "edit" => "<a href='" . link_to("/argument/edit/id/" . $argument->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "name" => "<b>" . $argument->get_name() . "</b><br>[" . $argument->get_pk_id() . "]", "action" => "<b>" . $action_mapper->get_name() . "</b><br>[" . $action_mapper->get_pk_id() . "]", "svar" => "<b>" . $state_variable_mapper->get_name() . "</b><br>[" . $state_variable_mapper->get_pk_id() . "]", "service" => "<b>" . $service_mapper->get_friendly_name() . "</b><br>[" . $service_mapper->get_pk_id() . "]", "device" => "<b>" . $device_mapper->get_friendly_name() . "</b><br>[" . $device_mapper->get_pk_id() . "]", "slave_controller" => "<b>" . $device_mapper->get_slave_controller() . "</b>", "remove" => "<a href='" . link_to("/argument/remove/id/" . $argument->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);