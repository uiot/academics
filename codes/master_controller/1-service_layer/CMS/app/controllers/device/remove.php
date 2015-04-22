<?php
if (isset($_POST['do'])):

    $device = new DeviceModel();
    $unique_device_id = escape_text($_POST["id"]);
    DeviceMapper::remove($unique_device_id);

    $text['title'] = "Remove Device";
    $text['message'] = "Device Removed Successfully";

    echo <<<BOX
<h2 >{$text['title']}</h2 >
<div class='row' >
	<div class='large-12 columns' >
		<div class='panel radius' >
			{$text['message']}
		</div >
		<a class='button success small radius' onclick='javascript: window.history.go(-2);' >Go Back</a >
	</div >
</div >
BOX;
else:

    $form = new Forms('Remove Device', '/device/remove/');
    $form->add_header('Warning');
    $form->add_hidden([
        'name' => 'do', 'value' => 'device_remove'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_text('Removing a Device also will remove all services, actions, state variables and arguments related to it. Remove it anyways?');
    $form->add_button('remove', '', 'Remove', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;