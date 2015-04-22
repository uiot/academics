<?php
if (isset($_POST['do'])):

    $service = new ServiceModel();
    $service->set_device($_POST["device"]);
    $service->set_friendly_name($_POST["friendly_name"]);
    $service->set_description($_POST["description"]);
    $service->set_service_type($_POST["service_type"]);
    $service->set_service_id($_POST["service_id"]);
    $service->set_scpdurl($_POST["scpdurl"]);
    $service->set_control_url($_POST["control_url"]);
    $service->set_event_suburl($_POST["event_suburl"]);
    $service->set_spec_version_major($_POST["spec_version_major"]);
    $service->set_spec_version_minor($_POST["spec_version_minor"]);
    $service->set_xml_link($_POST["xml_link"]);
    ServiceMapper::save($service);

    $text['title'] = "Add Service";
    $text['message'] = "Service Added Successfully";

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

    $devices_object = DeviceMapper::get_all();

    /* @var $devices_object DeviceModel[] */
    foreach ($devices_object as $device)
        $devices_array[] = ["value" => $device->get_pk_id(), "label" => $device->get_friendly_name() . " " . $device->get_pk_id() . " at " . $device->get_slave_controller()];

    $form = new Forms('Add Service', '/service/add/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'service_add'
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'friendly_name', 'class' => 'replace_space', 'placeholder' => 'the new service name', 'maxlength' => 30, 'value' => ''
    ], 'Service Friendly Name', 'please put a service friendly name');
    $form->add_select([
        'class' => 'replace_space', 'value' => '', 'name' => 'device'
    ], $devices_array, 'Device it Belongs', 'please select a device');
    $form->add_text_area([
        'name' => 'description', 'cols' => 5, 'rows' => 40
    ], '', 'Service Description', 'please put the service description');
    $form->add_button('add', '', 'Add', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;



