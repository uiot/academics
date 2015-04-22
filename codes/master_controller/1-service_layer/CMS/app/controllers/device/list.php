<?php
echo '<h2 > Available Devices </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "friendly_name" => "Friendly Name", "slave_controller" => "Slave Controller", "remove" => "Remove"];
$devices_object = DeviceMapper::get_all();

/* @var $devices_object DeviceModel[] */
foreach ($devices_object as $device):

    $local_table_row = [
        "edit" => "<a href='" . link_to("/device/edit/id/" . $device->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />[" . $device->get_pk_id() . "]", "slave_controller" => $device->get_slave_controller(), "remove" => "<a href='" . link_to("/device/remove/id/" . $device->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);