<?php
if (isset($_POST['do'])):

    $state_variable = new StateVariableModel();
    $unique_state_variable_id = escape_text($_POST["id"]);
    StateVariableMapper::remove($unique_state_variable_id);

    $text['title'] = "Remove State Variable";
    $text['message'] = "State Variable Removed Successfully";

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

    $form = new Forms('Remove State Variable', '/statevariable/remove/');
    $form->add_header('Warning');
    $form->add_hidden([
        'name' => 'do', 'value' => 'state_variable_remove'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_text('Removing this State Variable also will remove all arguments related to it. Remove it anyways?');
    $form->add_button('remove', '', 'Remove', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;