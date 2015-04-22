<?php

echo "<style>#Menu { display:none !important; }</style>";

if (isset($_GET['id']) && is_numeric($_GET['id'])):

    $id = escape_text($_GET['id']);

    echo "<form action='http://store.uiot.org/download/device/' method='POST' id='send_json'><input type='hidden' name='device_id' value='$id' /></form><script> $('#send_json').submit();</script>";

elseif (isset($_POST['device_details'])):

    $device_downloader = new DeviceDownloader($_POST["device_details"]);
    $response = $device_downloader->save_data();
    if ($response->status == "success"):

        echo '<h2 > Select a Slave Controller </h2 ><h5>Would you like to attach this Device to a Slave Controller?</h5>';

        $slave_controllers = SlaveControllerMapper::get_all();
        $table_fields = ["select" => "Select", "friendly_name" => "Unique Name"];
        $device_name = $response->device_id;
        $redirect_link = link_to('/device/edit/id/' . $device_name);

        /* @var $slave_controllers SlaveControllerMapper[] */
        foreach ($slave_controllers as $slave_controller):

            $local_row = [
                "select" => "<a class='button tiny' style='margin:0;' href='" . link_to("/get/slave/slave_id/" . $slave_controller->get_unic_name() . "/device_id/$device_name/h/true/") . "'>Click here to select</a>", "friendly_name" => "<b>" . $slave_controller->get_unic_name() . "</b><br />"
            ];

            $table_rows[] = $local_row;
        endforeach;

        Tables::create($table_fields, $table_rows);

        echo "<a class='button success small radius' href='" . link_to("/get/slave/cancel/true/h/true/") . "'>No, i don't want to attach.</a >";

    endif;
elseif (isset($_GET['cancel']) && ($_GET['cancel'])):

    echo <<<BOX
<div class='row' >
	<h2 >Success</h2 >
	<div class='large-12 columns' >
		<div class='panel radius' >
			Device downloaded with success. Don't forget to attach this device to a slave controller and to adjust state variables related to this device in order to get it working on your slave controller.
		</div >
		<a class='button success small radius' href='http://store.uiot.org/panel/list/'>Go Back to Store</a >
		<a class='button success small radius' onclick='window.top.location.href="$redirect_link"'>View Device</a >
	</div >
</div >
BOX;

elseif (isset($_GET['slave_id'])):

    $slave_id = escape_text($_GET['slave_id']);
    $device_ids = escape_text($_GET['device_id']);
    DeviceMapper::update_slave($slave_id, $device_ids);

    $redirect_link = link_to('/device/edit/id/' . $device_ids);

    echo <<<BOX
<div class='row' >
<h2 >Success</h2 >
	<div class='large-12 columns' >
		<div class='panel radius' >
			Device Downloaded and Attached Succeffully! Don't forget to adjust state variables related to this device in order to get it working on your slave controller.
		</div >
		<a class='button success small radius' href='http://store.uiot.org/panel/list/'>Go Back to Store</a >
		<a class='button success small radius' onclick='window.top.location.href="$redirect_link"'>View Device</a >
	</div >
</div >
BOX;
endif;