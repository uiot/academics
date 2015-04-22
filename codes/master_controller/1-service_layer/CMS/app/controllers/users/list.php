<?php

echo '<h2 > Users </h2 ><style >#addme { display: initial !important; }</style >';

$table_fields = ["edit" => "Edit", "name" => "Real Name", "user" => "Username", "remove" => "Remove"];
$users_object = UsersMapper::get_all();

/* @var $users_object UsersModel[] */
foreach ($users_object as $user):

    $local_table_row = [
        "edit" => "<a href='" . link_to("/users/edit/id/" . $user->get_pk_id()) . "'><i class='fa fa-pencil-square-o'></i></a>", "name" => "<b>" . $user->get_name() . "</b><br>[" . $user->get_pk_id() . "]", "user" => "<b>" . $user->get_username() . "</b><br>[" . $user->get_pk_id() . "]", "remove" => "<a href='" . link_to("/users/remove/id/" . $user->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);