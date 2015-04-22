<?php

echo '<h2 > Available Slave Controllers </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "name" => "Unic Name", "zigbee_id" => "ZigBee Id", "description" => "Description", "remove" => "Remove"];
$slave_controllers_object = SlaveControllerMapper::get_all();

/* @var $slave_controllers_object SalveControllerModel[] */
foreach ($slave_controllers_object as $slave_controller):

    $local_table_row = [
        "edit" => "<a href='" . link_to("/slavecontroller/edit/id/" . urlencode($slave_controller->get_unic_name())) . "'><i class='fa fa-pencil-square-o'></i></a>", "name" => $slave_controller->get_unic_name(), "zigbee_id" => $slave_controller->get_address(), "description" => substr($slave_controller->get_description(), 0, 20) . " .  .  . ", "remove" => "<a href='" . link_to("/slavecontroller/remove/id/" . urlencode($slave_controller->get_unic_name())) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);