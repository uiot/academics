<?php
if (isset($_POST['do'])):

    $action_model = new ActionModel();
    $action_model->set_service($_POST["service"]);
    $action_model->set_name($_POST["name"]);
    $unique_action_id = $_POST["id"];
    ActionMapper::update($action_model, $unique_action_id);

    $text['title'] = "Edit Action";
    $text['message'] = "Action Edited Successfully";

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
        $services_array [] = ['value' => $service->get_pk_id(), 'label' => $service->get_friendly_name() . " [" . $service->get_pk_id() . "] of " . $device_mapper->get_friendly_name() . " [" . $device_mapper->get_pk_id() . "] at " . $device_mapper->get_slave_controller()];
    endforeach;

    $action_mapper = ActionMapper::get_by_id(escape_text($_GET['id']));

    $form = new Forms('Edit Action', '/action/edit/');
    $form->add_header('General Configuration');
    $form->add_hidden([
        'name' => 'do', 'value' => 'action_edit'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_hidden([
        'name' => 'action_old_name', 'value' => $action_mapper->get_name()
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'name', 'class' => 'replace_space', 'placeholder' => 'the new action name', 'maxlength' => 30, 'value' => $action_mapper->get_name()
    ], 'Action Name', 'please put an action name');
    $form->add_select([
        'class' => 'replace_space', 'value' => $action_mapper->get_service(), 'name' => 'service'
    ], $services_array, 'Service Name', 'please select a service');
    $form->add_button('save', '', 'Save', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;

