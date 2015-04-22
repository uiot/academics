<?php

echo '<h2 > Available State Variables </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "name" => "State variable", "service" => "Service it belongs", "device" => "Device it belongs", "slave_controller" => "Slave Controller", "remove" => "Remove"];
$state_variables_object = StateVariableMapper::get_all();

/* @var $state_variables_object StateVariableModel[] */
foreach ($state_variables_object as $state_variable):

    $service_mapper = ServiceMapper::get_by_id($state_variable->get_service());
    $device_mapper = DeviceMapper::get_by_id($service_mapper->get_device());

    $local_table_row = [
        "edit" => "<a href='" . link_to("/statevariable/edit/id/" . $state_variable->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "name" => "<b>" . $state_variable->get_name() . "</b><br>[" . $state_variable->get_pk_id() . "]", "service" => "<b>" . $service_mapper->get_friendly_name() . "</b><br>[" . $service_mapper->get_pk_id() . "]", "device" => "<b>" . $device_mapper->get_friendly_name() . "</b><br>[" . $device_mapper->get_pk_id() . "]", "slave_controller" => "<b>" . $device_mapper->get_slave_controller() . "</b>", "remove" => "<a href='" . link_to("/statevariable/remove/id/" . $state_variable->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);