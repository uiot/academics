<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])):

    echo "<style>#Menu { display:none !important; }</style>";

    $device_id = escape_text($_GET['id']);
    $device_object = new Device($device_id);

    $device_json = $device_object->get_device_json();

    echo "<form action='http://store.uiot.org/upload/device' method='POST' id='send_json'><input type='hidden' name='device' value='$device_json' /></form><script> $('#send_json').submit();</script>";
else:

    echo '<h2 > Select a Device </h2 >';

    $devices = DeviceMapper::get_all();
    $table_fields = ["select" => "Select", "friendly_name" => "Friendly Name", "slave_controller" => "Slave Controller"];

    foreach ($devices as $key => $device):

        $local_row = [
            "select" => "<a href='" . link_to("/get/device/id/" . $device->get_pk_id()) . "'><i class='fa fa-check'></i></a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />[" . $device->get_pk_id() . "]", "slave_controller" => $device->get_slave_controller()
        ];

        $table_rows[] = $local_row;
    endforeach;

    Tables::create($table_fields, $table_rows);
endif;