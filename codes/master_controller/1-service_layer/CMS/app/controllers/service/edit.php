<?php
if (isset($_POST['do'])):

    $service_mapper = new ServiceModel();
    $service_mapper->set_device($_POST["device"]);
    $service_mapper->set_friendly_name($_POST["friendly_name"]);
    $service_mapper->set_description($_POST["description"]);
    //$service_mapper->set_service_type ( $_POST[ "service_type" ] );
    //$service_mapper->set_service_id ( $_POST[ "service_id" ] );
    //$service_mapper->set_scpdurl ( $_POST[ "scpdurl" ] );
    //$service_mapper->set_control_url ( $_POST[ "control_url" ] );
    //$service_mapper->set_event_suburl ( $_POST[ "event_suburl" ] );
    //$service_mapper->set_spec_version_major ( $_POST[ "spec_version_major" ] );
    //$service_mapper->set_spec_version_minor ( $_POST[ "spec_version_minor" ] );
    //$service_mapper->set_xml_link ( $_POST[ "xml_link" ] );
    $unique_service_id = escape_text($_POST["id"]);
    ServiceMapper::update($service_mapper, $unique_service_id);

    $text['title'] = "Edit Service";
    $text['message'] = "Service Edited Successfully";

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
        $devices_array [] = ["value" => $device->get_pk_id(), "label" => $device->get_friendly_name() . " " . $device->get_pk_id() . " at " . $device->get_slave_controller()];

    $service_mapper = ServiceMapper::get_by_id(escape_text($_GET['id']));

    $form = new Forms('Edit Service', '/service/edit/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'service_edit'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'friendly_name', 'class' => 'replace_space', 'placeholder' => 'the new service name', 'maxlength' => 30, 'value' => $service_mapper->get_friendly_name()
    ], 'Service Friendly Name', 'please put a service friendly name');
    $form->add_select([
        'class' => 'replace_space', 'value' => $service_mapper->get_device(), 'name' => 'device'
    ], $devices_array, 'Device it Belongs', 'please select a device');
    $form->add_text_area([
        'name' => 'description', 'cols' => 5, 'rows' => 40
    ], $service_mapper->get_description(), 'Service Description', 'please put the service description');
    $form->add_header('Automatic Compatibility Configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'service_type', 'class' => 'replace_space', 'placeholder' => 'the new service type', 'maxlength' => 30, 'value' => $service_mapper->get_service_type(), 'disabled' => 'disabled'
    ], 'Service Type', 'please put a service type');
    $form->add_input([
        'type' => 'text', 'name' => 'service_id', 'class' => 'replace_space', 'placeholder' => 'the new service id', 'maxlength' => 30, 'value' => $service_mapper->get_service_id(), 'disabled' => 'disabled'
    ], 'Service ID', 'please put a service id');
    $form->add_input([
        'type' => 'text', 'name' => 'scpdurl', 'class' => 'replace_space', 'placeholder' => 'the new service scpd url', 'maxlength' => 30, 'value' => $service_mapper->get_scpdurl(), 'disabled' => 'disabled'
    ], 'SCPD URL', 'please put a scpd url');
    $form->add_input([
        'type' => 'text', 'name' => 'control_url', 'class' => 'replace_space', 'placeholder' => 'the new service control url', 'maxlength' => 30, 'value' => $service_mapper->get_control_url(), 'disabled' => 'disabled'
    ], 'Control URL', 'please put a service control url');
    $form->add_input([
        'type' => 'text', 'name' => 'event_suburl', 'class' => 'replace_space', 'placeholder' => 'the new service event sub url', 'maxlength' => 30, 'value' => $service_mapper->get_event_suburl(), 'disabled' => 'disabled'
    ], 'Event SubURL', 'please put a service event sub url');
    $form->add_input([
        'type' => 'text', 'name' => 'spec_version_major', 'class' => 'replace_space', 'placeholder' => 'the new service specific version major', 'maxlength' => 30, 'value' => $service_mapper->get_spec_version_major(), 'disabled' => 'disabled'
    ], 'Specific Version Major', 'please put a service specific version major');
    $form->add_input([
        'type' => 'text', 'name' => 'spec_version_minor', 'class' => 'replace_space', 'placeholder' => 'the new service specific version minor', 'maxlength' => 30, 'value' => $service_mapper->get_spec_version_minor(), 'disabled' => 'disabled'
    ], 'Specific Version Minor', 'please put a service specific version minor');
    $form->add_button('save', '', 'Save', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;