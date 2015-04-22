<?php
if (isset($_POST['do'])):

    $users = new UsersModel();
    $unique_user_id = escape_text($_POST["id"]);
    UsersMapper::remove($unique_user_id);

    $text['title'] = "Remove Users";
    $text['message'] = "Users Removed Successfully";

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

    $form = new Forms('Remove User', '/users/remove/');
    $form->add_header('Warning');
    $form->add_hidden([
        'name' => 'do', 'value' => 'user_remove'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_text('Removing this User cannot Be Undone, Do you want to Continue?');
    $form->add_button('remove', '', 'Remove', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;