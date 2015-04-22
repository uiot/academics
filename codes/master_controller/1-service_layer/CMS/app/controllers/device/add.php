<?php
if (isset($_POST['do'])):

    $device = new DeviceModel();
    $device->set_udn($_POST["udn"]);
    $device->set_slave_controller($_POST["slave_controller"]);
    $device->set_friendly_name($_POST["friendly_name"]);
    $device->set_device_type($_POST["device_type"]);
    $device->set_manufacturer($_POST["manufacturer"]);
    $device->set_manufacturer_url($_POST["manufacturer_url"]);
    $device->set_model_description($_POST["model_description"]);
    $device->set_model_name($_POST["model_name"]);
    $device->set_model_number($_POST["model_number"]);
    $device->set_model_url($_POST["model_url"]);
    $device->set_serial_number($_POST["serial_number"]);
    $device->set_upc($_POST["upc"]);
    $device->set_spec_version_major($_POST["spec_version_major"]);
    $device->set_spec_version_minor($_POST["spec_version_minor"]);
    $device->set_xml_link($_POST["xml_link"]);
    DeviceMapper::save($device);

    $text['title'] = "Add Device";
    $text['message'] = "Device Added Successfully";

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

    $slave_controller_object = SlaveControllerMapper::get_all();

    /* @var $slave_controller_object SlaveControllerModel[] */
    foreach ($slave_controller_object as $slave_controller)
        $slave_controller_array[] = ["value" => $slave_controller->get_unic_name(), "label" => $slave_controller->get_unic_name()];

    $form = new Forms('Add Device', '/device/add/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'device_add'
    ]);
    $form->add_select([
        'class' => 'replace_space', 'value' => '', 'name' => 'slave_controller'
    ], $slave_controller_array, 'Slave Controller it Belongs', 'please select a slave controller');
    $form->add_input([
        'type' => 'text', 'name' => 'friendly_name', 'class' => 'replace_space', 'placeholder' => 'the new device name', 'maxlength' => 30, 'value' => ''
    ], 'Argument Name', 'please put a device friendly name');
    $form->add_header('Manufacturer configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'manufacturer', 'class' => 'replace_space', 'placeholder' => 'the new device manufacturers', 'maxlength' => 30, 'value' => ''
    ], 'Manufacturer', 'please put a manufacturer');
    $form->add_input([
        'type' => 'text', 'name' => 'manufacturer_url', 'class' => 'replace_space', 'placeholder' => 'the new device manufacturer url', 'maxlength' => 30, 'value' => ''
    ], 'Manufacturer URL', 'please put a manufacturer url');
    $form->add_header('Model configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'model_description', 'class' => 'replace_space', 'placeholder' => 'the new device description', 'maxlength' => 30, 'value' => ''
    ], 'Model Description', 'please put a description for the device');
    $form->add_input([
        'type' => 'text', 'name' => 'model_name', 'class' => 'replace_space', 'placeholder' => 'the new device name', 'maxlength' => 30, 'value' => ''
    ], 'Model Name', 'please put a name for the device');
    $form->add_input([
        'type' => 'text', 'name' => 'model_number', 'class' => 'replace_space', 'placeholder' => 'the new device model number', 'maxlength' => 30, 'value' => ''
    ], 'Model Number', 'please put a model for the device');
    $form->add_input([
        'type' => 'text', 'name' => 'model_url', 'class' => 'replace_space', 'placeholder' => 'the new device url', 'maxlength' => 30, 'value' => ''
    ], 'Model Url', 'please put an url for the device');
    $form->add_header('Device configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'serial_number', 'class' => 'numbers_only', 'placeholder' => 'the new device serial number', 'maxlength' => 30, 'value' => ''
    ], 'Serial Number', 'please put a serial number for the device');
    $form->add_input([
        'type' => 'text', 'name' => 'upc', 'class' => 'numbers_only', 'placeholder' => 'the new device upc code', 'maxlength' => 30, 'value' => ''
    ], 'The Device Universal Code (UPC)', 'please put a upc code for the device');
    $form->add_button('add', '', 'Add', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;