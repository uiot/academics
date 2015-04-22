<?php
if (isset($_POST['do'])):

    $users = new UsersModel();
    $users->set_name($_POST["name"]);
    $users->set_username($_POST["username"]);
    $users->set_password($_POST["password"]);
    $users->set_pk_id($_POST["id"]);
    $unique_user_id = escape_text($_POST["id"]);
    UsersMapper::update($users, $unique_user_id);

    $text['title'] = "Edit User";
    $text['message'] = "Users Edited Successfully";

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

    $user_mapper = UsersMapper::get_by_id(escape_text($_GET['id']));

    $form = new Forms('Edit User', '/users/edit/');
    $form->add_header('User Information');
    $form->add_hidden([
        'name' => 'do', 'value' => 'user_edit'
    ]);
    $form->add_hidden([
        'name' => 'id', 'value' => $_GET["id"]
    ]);
    $form->add_input([
        'type' => 'text', 'name' => 'name', 'class' => '', 'placeholder' => 'the new user real name', 'maxlength' => 30, 'value' => $user_mapper->get_name()
    ], 'Real Name', 'please put a real name for the user');
    $form->add_input([
        'type' => 'text', 'name' => 'username', 'class' => 'replace_space', 'placeholder' => 'the new user username', 'maxlength' => 30, 'value' => $user_mapper->get_username()
    ], 'User Name', 'please put a username for the user');
    $form->add_input([
        'type' => 'password', 'name' => 'password', 'class' => 'replace_space', 'placeholder' => 'the new user password', 'maxlength' => 30, 'value' => ''
    ], 'Password', 'please put a password for the user');
    $form->add_input([
        'type' => 'password', 'name' => 'password_repeat', 'class' => 'replace_space', 'id' => 'password', 'placeholder' => 'repeat the password', 'maxlength' => 30, 'value' => ''
    ], 'Repeat Password', 'the password doesnt matchs', 'password');
    $form->add_button('save', '', 'Save', 'smit();');
    $form->render_form();
endif;