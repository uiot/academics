<?php

echo '<h2 > Available Actions </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "name" => "Action", "service" => "Service it belongs", "device" => "Device it belongs", "slave_controller" => "Slave Controller", "remove" => "Remove"];
$actions_object = ActionMapper::get_all();

/* @var $actions_object ActionsModel[] */
foreach ($actions_object as $action):

    $service_mapper = ServiceMapper::get_by_id($action->get_service());
    $device_mapper = DeviceMapper::get_by_id($service_mapper->get_device());

    $local_table_row = [
        "edit" => "<a href='" . link_to("/action/edit/id/" . $action->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "name" => "<b>" . $action->get_name() . "</b><br/>[" . $action->get_pk_id() . "]", "service" => "<b>" . $service_mapper->get_friendly_name() . "</b><br>[" . $service_mapper->get_pk_id() . "]", "device" => "<b>" . $device_mapper->get_friendly_name() . "</b><br>[" . $device_mapper->get_pk_id() . "]", "slave_controller" => "<b>" . $device_mapper->get_slave_controller() . "</b>", "remove" => "<a href='" . link_to("/action/remove/id/" . $action->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);