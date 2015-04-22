<?php
if (isset($_POST['do'])):

    $slave_controller = new SlaveControllerModel();
    $unique_slave_controller_id = escape_text($_POST["id"]);
    SlaveControllerMapper::remove($unique_slave_controller_id);

    $text['title'] = "Remove Slave Controller";
    $text['message'] = "Slave Controller Removed Successfully";

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

    $form = new Forms('Remove Slave Controller', '/slavecontroller/remove/');
    $form->add_header('Warning');
    $form->add_hidden([
        'name' => 'do', 'value' => 'slave_controller_remove'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_text('Removing a Slave Controller also will remove all devices, services, actions, state variables and arguments related to it. Remove it anyways?');
    $form->add_button('remove', '', 'Remove', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;