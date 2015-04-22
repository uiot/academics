<?php
if (isset($_POST['do'])):

    $slave_controller = new SlaveControllerModel();
    $slave_controller->set_unic_name($_POST["unic_name"]);
    $slave_controller->set_type($_POST["type"]);
    $slave_controller->set_address($_POST["address"]);
    $slave_controller->set_description($_POST["description"]);
    $unique_slave_controller_id = escape_text($_POST["id"]);
    SlaveControllerMapper::update($slave_controller, $unique_slave_controller_id);

    $text['title'] = "Edit Slave Controller";
    $text['message'] = "Slave Controller Edited Successfully";

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

    $slave_controller_mapper = SlaveControllerMapper::get_by_id(escape_text($_GET['id']));

    $form = new Forms('Edit Slave Controller', '/slavecontroller/edit/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'slave_controller_add'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'unic_name', 'class' => 'replace_space', 'placeholder' => 'the new slave controller unique name', 'maxlength' => 30, 'value' => $slave_controller_mapper->get_unic_name()
    ], 'Unique Name', 'please put an unique name');
    $form->add_input([
        'type' => 'text', 'name' => 'type', 'class' => 'replace_space', 'placeholder' => 'the new slave controller type', 'maxlength' => 30, 'value' => $slave_controller_mapper->get_type()
    ], 'Type', 'please put an type');
    $form->add_input([
        'type' => 'text', 'name' => 'address', 'class' => 'replace_space', 'placeholder' => 'the new slave controller zig bee id', 'maxlength' => 30, 'value' => $slave_controller_mapper->get_address()
    ], 'ZigBee ID', 'please put an zig bee id');
    $form->add_text_area([
        'name' => 'description', 'cols' => 40, 'rows' => 5
    ], $slave_controller_mapper->get_description(), 'Slave Controller Description', 'please put the slave controller description');
    $form->add_button('save', '', 'Save', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;