<?php
if (isset($_POST['do'])):

    $argument = new ArgumentModel();
    $argument->set_action($_POST["action"]);
    $argument->set_name($_POST["name"]);
    $argument->set_direction($_POST["direction"]);
    $argument->set_ret_val($_POST["ret_val"]);
    $argument->set_related_state_variable($_POST["related_state_variable"]);
    ArgumentMapper::save($argument);

    $text['title'] = "Add Argument";
    $text['message'] = "Argument Added Successfully";

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

    $actions_object = ActionMapper::get_all();

    /* @var $actions_object ActionModel[] */
    foreach ($actions_object as $action):
        $service_mapper = ServiceMapper::get_by_id($action->get_service());
        $device_mapper = DeviceMapper::get_by_id($service_mapper->get_device());
        $actions_array[] = ["value" => $action->get_pk_id(), "label" => $action->get_name() . " [ " . $action->get_pk_id() . " ], " . $service_mapper->get_friendly_name() . " [" . $service_mapper->get_pk_id() . "] of " . $device_mapper->get_friendly_name() . " [" . $device_mapper->get_pk_id() . "] at " . $device_mapper->get_slave_controller()];
    endforeach;

    $state_variable_object = StateVariableMapper::get_all();

    /* @var $state_variable_object StateVariableModel[] */
    foreach ($state_variable_object as $state_variable):
        $service_mapper = ServiceMapper::get_by_id($state_variable->get_service());
        $device_mapper = DeviceMapper::get_by_id($service_mapper->get_device());
        $service_arrays[] = ["value" => $state_variable->get_pk_id(), "label" => $state_variable->get_name() . " [ " . $state_variable->get_pk_id() . " ], " . $service_mapper->get_friendly_name() . " [" . $service_mapper->get_pk_id() . "] of " . $device_mapper->get_friendly_name() . " [" . $device_mapper->get_pk_id() . "] at " . $device_mapper->get_slave_controller()];
    endforeach;

    $form = new Forms('Add Argument', '/argument/add/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'argument_add'
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'name', 'class' => 'replace_space', 'placeholder' => 'the new argument name', 'maxlength' => 30, 'value' => ''
    ], 'Argument Name', 'please put an argument name');
    $form->add_select([
        'class' => 'replace_space', 'value' => '', 'name' => 'action'
    ], $actions_array, 'Action Name', 'please select an action');
    $form->add_select([
        'class' => 'replace_space', 'value' => '', 'name' => 'related_state_variable'
    ], $service_arrays, 'State Variable', 'please select a state variable');
    $form->add_select([
        'class' => 'replace_space', 'value' => '', 'name' => 'direction'
    ], [
        [
            "value" => "in", "label" => "In"
        ], [
            "value" => "out", "label" => "Out"
        ]
    ], 'Direction', 'please select select a direction');
    $form->add_input([
        'type' => 'text', 'name' => 'ret_val', 'class' => 'replace_space', 'placeholder' => 'the argument return value', 'maxlength' => 30, 'value' => ''
    ], 'Return Value', 'please put an return value');
    $form->add_button('add', '', 'Add', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;
