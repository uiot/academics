<?php
if (isset($_POST['do'])):

    $state_variable = new StateVariableModel();
    $state_variable->set_service($_POST["service"]);
    $state_variable->set_name($_POST["name"]);
    $state_variable->set_send_events($_POST["send_events"]);
    $state_variable->set_multicast($_POST["multicast"]);
    $state_variable->set_data_type($_POST["data_type"]);
    $state_variable->set_default_value($_POST["default_value"]);
    $state_variable->set_reading_circuit_type($_POST["reading_circuit_type"]);
    $state_variable->set_writing_circuit_type($_POST["writing_circuit_type"]);
    $state_variable->set_reading_circuit_pin($_POST["reading_circuit_pin"]);
    $state_variable->set_writing_circuit_pin($_POST["writing_circuit_pin"]);
    $state_variable->set_reading_circuit_baudrate($_POST["reading_circuit_baudrate"]);
    $state_variable->set_writing_circuit_baudrate($_POST["writing_circuit_baudrate"]);
    $unique_state_variable_id = escape_text($_POST["id"]);
    StateVariableMapper::update($state_variable, $unique_state_variable_id);

    $text['title'] = "Edit State Variable";
    $text['message'] = "State Variable Edited Successfully";

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

    $services_object = ServiceMapper::get_all();

    /* @var $services_object ServiceModel[] */
    foreach ($services_object as $service):
        $device_mapper = DeviceMapper::get_by_id($service->get_device());
        $services_array[] = ["value" => $service->get_pk_id(), "label" => $service->get_friendly_name() . " " . $service->get_pk_id() . " of " . $device_mapper->get_friendly_name() . " " . $device_mapper->get_pk_id() . " at " . $device_mapper->get_slave_controller()];
    endforeach;

    $state_variable_mapper = StateVariableMapper::get_by_id(escape_text($_GET['id']));

    $form = new Forms('Edit State Variable', '/statevariable/edit/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'state_variable_edit'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'name', 'class' => 'replace_space', 'placeholder' => 'the new state variable name', 'maxlength' => 30, 'value' => $state_variable_mapper->get_name()
    ], 'State Variable Name', 'please put a state variable name');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_service(), 'name' => 'service'
    ], $services_array, 'Service Name', 'please select an service');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_data_type(), 'name' => 'data_type'
    ], [
        [
            "value" => 'string', "label" => 'String'
        ], [
            "value" => 'int', "label" => 'Integer'
        ], [
            "value" => 'boolean', "label" => 'Boolean'
        ]
    ], 'Data Type', 'please select a data type');
    $form->add_header('UPnP Configuration');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_send_events(), 'name' => 'send_events'
    ], [
        [
            "value" => 'yes', "label" => 'Yes'
        ], [
            "value" => 'no', "label" => 'No'
        ]
    ], 'Send Events', 'please select the events to be sent');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_multicast(), 'name' => 'multicast'
    ], [
        [
            "value" => 'yes', "label" => 'Yes'
        ], [
            "value" => 'no', "label" => 'No'
        ]
    ], 'MultiCast', 'please choose if is multi cast');
    $form->add_input([
        'type' => 'text', 'name' => 'default_value', 'class' => 'replace_space', 'placeholder' => 'the new state variable default value', 'maxlength' => 30, 'value' => $state_variable_mapper->get_default_value()
    ], 'Default Value', 'please put a default value');
    $form->add_header('Reading circuit configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'reading_circuit_pin', 'class' => 'replace_space', 'placeholder' => 'the new state variable pin id', 'maxlength' => 30, 'value' => $state_variable_mapper->get_reading_circuit_pin()
    ], 'Reading Circuit Pin', 'please put a circuit pin id');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_reading_circuit_type(), 'name' => 'reading_circuit_type'
    ], [
        [
            "value" => 'DIGITAL', "label" => 'Digital'
        ], [
            "value" => 'ANALOG', "label" => 'Analog'
        ], [
            "value" => 'PWM', "label" => 'PWM'
        ], [
            "value" => 'DIGITAL', "label" => 'UART'
        ]
    ], 'Reading Circuit Type', 'please select a reading circuit type');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_reading_circuit_baudrate(), 'name' => 'reading_circuit_baudrate'
    ], [
        [
            "value" => '0', "label" => '0'
        ], [
            "value" => '300', "label" => '300'
        ], [
            "value" => '600', "label" => '600'
        ], [
            "value" => '1200', "label" => '1200'
        ], [
            "value" => '2400', "label" => '2400'
        ], [
            "value" => '4800', "label" => '4800'
        ], [
            "value" => '9600', "label" => '9600'
        ], [
            "value" => '14400', "label" => '14400'
        ], [
            "value" => '19200', "label" => '19200'
        ], [
            "value" => '28800', "label" => '28800'
        ], [
            "value" => '38400', "label" => '38400'
        ], [
            "value" => '57600', "label" => '57600'
        ], [
            "value" => '115200', "label" => '115200'
        ]
    ], 'Reading Circuit Baudrate', 'please select the baudrate rate');
    $form->add_header('Writing circuit configuration');
    $form->add_input([
        'type' => 'text', 'name' => 'writing_circuit_pin', 'class' => 'replace_space', 'placeholder' => 'the new state variable pin id', 'maxlength' => 30, 'value' => $state_variable_mapper->get_writing_circuit_pin()
    ], 'Writing Circuit Pin', 'please put a circuit pin id');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_writing_circuit_type(), 'name' => 'writing_circuit_type'
    ], [
        [
            "value" => 'DIGITAL', "label" => 'Digital'
        ], [
            "value" => 'ANALOG', "label" => 'Analog'
        ], [
            "value" => 'PWM', "label" => 'PWM'
        ], [
            "value" => 'DIGITAL', "label" => 'UART'
        ]
    ], 'Writing Circuit Type', 'please select a reading circuit type');
    $form->add_select([
        'class' => 'replace_space', 'value' => $state_variable_mapper->get_writing_circuit_baudrate(), 'name' => 'writing_circuit_baudrate'
    ], [
        [
            "value" => '0', "label" => '0'
        ], [
            "value" => '300', "label" => '300'
        ], [
            "value" => '600', "label" => '600'
        ], [
            "value" => '1200', "label" => '1200'
        ], [
            "value" => '2400', "label" => '2400'
        ], [
            "value" => '4800', "label" => '4800'
        ], [
            "value" => '9600', "label" => '9600'
        ], [
            "value" => '14400', "label" => '14400'
        ], [
            "value" => '19200', "label" => '19200'
        ], [
            "value" => '28800', "label" => '28800'
        ], [
            "value" => '38400', "label" => '38400'
        ], [
            "value" => '57600', "label" => '57600'
        ], [
            "value" => '115200', "label" => '115200'
        ]
    ], 'Writing Circuit Baudrate', 'please select the baudrate rate');
    $form->add_button('save', '', 'Save', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;