<?php
if (isset($_POST['do'])):

    $app_model = new AppsModel();
    $app_unique_id = escape_text($_POST["id"]);
    AppsMapper::remove($app_unique_id);
    RemoveAppFile::make($app_unique_id);

    $text['title'] = "Remove App";
    $text['message'] = "App Removed Successfully";

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

    $form = new Forms('Remove App', '/apps/remove/');
    $form->add_header('Warning');
    $form->add_hidden([
        'name' => 'do', 'value' => 'action_remove'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_text('Removing this App Your Local Devices will Be Useless, Do You want to Proceed?');
    $form->add_button('remove', '', 'Remove', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;