<?php
if (isset($_POST['do'])):

    $user = new UsersModel();
    $user->set_name($_POST["name"]);
    $user->set_username($_POST["username"]);
    $user->set_password($_POST["password"]);
    UsersMapper::save($user);

    $text['title'] = "Add Users";
    $text['message'] = "Users Added Successfully";

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

    $form = new Forms('Add User', '/users/add');
    $form->add_header('User Information');
    $form->add_hidden([
        'name' => 'do', 'value' => 'save'
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'name', 'class' => '', 'placeholder' => 'the new user real name', 'maxlength' => 30, 'value' => ''
    ], 'Real Name', 'please put a real name for the user');
    $form->add_input([
        'type' => 'text', 'name' => 'username', 'class' => 'replace_space', 'placeholder' => 'the new user username', 'maxlength' => 30, 'value' => ''
    ], 'User Name', 'please put a username for the user');
    $form->add_input([
        'type' => 'password', 'name' => 'password', 'class' => 'replace_space', 'placeholder' => 'the new user password', 'maxlength' => 30, 'value' => ''
    ], 'Password', 'please put a password for the user');
    $form->add_input([
        'type' => 'password', 'name' => 'password_repeat', 'class' => 'replace_space', 'id' => 'password', 'placeholder' => 'repeat the password', 'maxlength' => 30, 'value' => ''
    ], 'Repeat Password', 'the password doesnt matchs', 'password');
    $form->add_button('add', '', 'Add', 'smit();');
    $form->add_button('cancel', '', 'Cancel', 'history.back()');
    $form->render_form();
endif;