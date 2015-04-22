<?php

echo '<h2 > Available Services </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "friendly_name" => "Service", "device" => "Device it belongs", "slave_controller" => "Slave Controller", "remove" => "Remove"];
$services_object = ServiceMapper::get_all();

/* @var $services_object ServiceModel[] */
foreach ($services_object as $service):

    $device = DeviceMapper::get_by_id($service->get_device());

    $local_row = [
        "edit" => "<a href='" . link_to("/service/edit/id/" . $service->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "friendly_name" => "<b>" . $service->get_friendly_name() . "</b><br>[" . $service->get_pk_id() . "]", "device" => "<b>" . $device->get_friendly_name() . "</b><br>[" . $device->get_pk_id() . "]", "slave_controller" => $device->get_slave_controller(), "remove" => "<a href='" . link_to("/service/remove/id/" . $service->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_row;
endforeach;

Tables::create($table_fields, $table_rows);