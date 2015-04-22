<?php

echo "<style>#Menu { display:none !important; }</style>";

if (isset($_GET['app_id']) && is_numeric($_GET['app_id'])):

    $app_id_from_store = escape_text($_GET['app_id']);

    echo "<form action='http://store.uiot.org/download/app/' method='POST' id='send_json'><input type='hidden' name='app_id' value='$app_id_from_store' /></form><script> $('#send_json').submit();</script>";

elseif (isset($_GET['dev_id']) && is_numeric($_GET['dev_id'])):

    $app_id_from_store = escape_text($_GET['dev_id']);
    if (AppsMapper::check_by_store($app_id_from_store) > 0):
        $app_local_content = AppsMapper::get_by_store($app_id_from_store);
        $app_id_from_local = $app_local_content->get_pk_id();
        echo "<script>window.location.href='/apps/edit/id/$app_id_from_local';</script>";
    endif;

    echo "<form action='http://store.uiot.org/download/app/' method='POST' id='send_json'><input type='hidden' name='dev_id' value='$app_id_from_store' /></form><script> $('#send_json').submit();</script>";

elseif (isset($_POST['app_details'])):

    $_SESSION['d'] = $_POST['app_details'];
    $_SESSION['e'] = $_POST['app_code'];

    $app_details_from_store = json_decode($_POST['app_details'], true);
    if (AppsMapper::check_by_store($app_details_from_store['store_id']) > 0):
        $app_details_from_local = AppsMapper::get_by_store($app_details_from_store['store_id']);
        if ($app_details_from_store['version'] != $app_details_from_local->get_version()):
            echo "<script>window.location.href='/get/app/app_update/{$app_details_from_local->get_pk_id()}';</script>";
        endif;
    endif;

    echo '<h2 > Select a Device for your App</h2 > <h4>We will show the Compatible Devices for You</h4>';
    echo '<h5> That\'s the Devices that you Have that is Compatible.</h5>';

    $table_fields_a = ["select" => "Select", "friendly_name" => "Device Name"];
    $devices_object = DeviceMapper::get_by_store($app_details_from_store['device']);
    foreach ($devices_object as $device):

        $local_row = [
            "select" => "<a class='button tiny' style='margin:0;' href='" . link_to("/get/app/dev_id_app/{$device->get_pk_id()}/a/true/") . "'>Click here to select</a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />"
        ];

        $table_rows_a[] = $local_row;
    endforeach;

    Tables::create($table_fields_a, $table_rows_a);

    echo '<h5> That\'s Are All Your Devices you can choose it but BEWARE that\'s Devices isn\'t probably compatible.</h5>';

    $table_fields = ["select" => "Select", "friendly_name" => "Device Name"];
    $devices_object = DeviceMapper::get_all();
    foreach ($devices_object as $device):

        $local_row = [
            "select" => "<a class='button tiny' style='margin:0;' href='" . link_to("/get/app/dev_id_app/{$device->get_pk_id()}/b/true/") . "'>Click here to select</a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />"
        ];

        $table_rows[] = $local_row;
    endforeach;

    Tables::create($table_fields, $table_rows);

elseif (isset($_POST['dev_details'])):

    $_SESSION['d'] = $_POST['dev_details'];

    $app_details_from_store = json_decode($_POST['dev_details'], true);
    if (AppsMapper::check_by_store($app_details_from_store['store_id']) > 0):
        $app_details_from_local = AppsMapper::get_by_store($app_details_from_store['store_id']);
        echo "<script>window.location.href='/apps/edit/id/{$app_details_from_local->get_pk_id ()}';</script>";
    endif;

    echo '<h2 > Select a Device for your App (Developer)</h2 > <h4>We will show the Compatible Devices for You</h4>';
    echo '<h5> That\'s the Devices that you Have that is Compatible.</h5>';

    $table_fields_a = ["select" => "Select", "friendly_name" => "Device Name"];
    $devices_object = DeviceMapper::get_by_store($app_details_from_store['device']);
    foreach ($devices_object as $device):

        $local_row = [
            "select" => "<a class='button tiny' style='margin:0;' href='" . link_to("/get/app/dev_id_dev/{$device->get_pk_id()}/a/true/") . "'>Click here to select</a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />"
        ];

        $table_rows_a[] = $local_row;
    endforeach;

    Tables::create($table_fields_a, $table_rows_a);

    echo '<h5> That\'s Are All Your Devices you can choose it but BEWARE that\'s Devices isn\'t probably compatible.</h5>';

    $table_fields = ["select" => "Select", "friendly_name" => "Device Name"];
    $devices_object = DeviceMapper::get_all();
    foreach ($devices_object as $device):

        $local_row = [
            "select" => "<a class='button tiny' style='margin:0;' href='" . link_to("/get/app/dev_id_dev/{$device->get_pk_id()}/b/true/") . "'>Click here to select</a>", "friendly_name" => "<b>" . $device->get_friendly_name() . "</b><br />"
        ];

        $table_rows[] = $local_row;
    endforeach;

    Tables::create($table_fields, $table_rows);

elseif (isset($_GET['dev_id_app'])):

    if (isset($_GET['a'])):
        $internal_message = 'Okay The Application Was Added, And the Device that was Setted is Compatible.';
    elseif (isset($_GET['b'])):
        $internal_message = 'Okay The Application Was Added, Warning! The Device that Was Selected isn\'t Trusted/Compatible.';
    endif;

    $device_id = escape_text($_GET['dev_id_app']);
    if (isset($_SESSION['d'])):
        $app_details_from_store = json_decode($_SESSION['d'], true);
        $app_model = new AppsModel();
        $app_model->set_public_name($app_details_from_store["name"]);
        $app_model->set_version($app_details_from_store["version"]);
        $app_model->set_author($app_details_from_store["author"]);
        $app_model->set_name($app_details_from_store["name"]);
        $app_model->set_description($app_details_from_store["description"]);
        $app_model->set_device_id($device_id);
        $app_model->set_store_id($app_details_from_store["store_id"]);
        $app_created_id = AppsMapper::save($app_model);
        $redirect_link = link_to("/apps/open/id/{$app_created_id}");
        CreateAppFile::make($_SESSION['d'], $app_created_id, $_SESSION['e']);
    else:
        $redirect_link = link_to('/apps/list/');
        $internal_message = 'Totally Error! App Doesn\'t Was Created';
    endif;

    $_SESSION['d'] = null;
    $_SESSION['e'] = null;
    $app_details_from_store = null;
    $app_code_from_store = null;

    echo <<<BOX
<div class='row' >
	<h2 >Success</h2 >
	<div class='large-12 columns' >
		<div class='panel radius' >
			App Downloaded Sueccesfully! And Added to Local App List.
		    <b>Internal Message:</b> $internal_message
		</div >
		<a class='button success small radius' href='http://store.uiot.org/panel/list/'>Go Back to Store</a >
		<a class='button success small radius' onclick='window.top.location.href="$redirect_link"'>View App</a >
	</div >
</div >
BOX;
elseif (isset($_GET['dev_id_dev'])):

    if (isset($_GET['a'])):
        $internal_message = 'Okay The Application Was Added, And the Device that was Setted is Compatible.';
    elseif (isset($_GET['b'])):
        $internal_message = 'Okay The Application Was Added, Warning! The Device that Was Selected isn\'t Trusted/Compatible.';
    endif;

    $device_id = escape_text($_GET['dev_id_dev']);
    if (isset($_SESSION['d'])):
        $app_details_from_store = json_decode($_SESSION['d'], true);
        $app_model = new AppsModel();
        $app_model->set_public_name($app_details_from_store["name"]);
        $app_model->set_version($app_details_from_store["version"]);
        $app_model->set_author($app_details_from_store["author"]);
        $app_model->set_name($app_details_from_store["name"]);
        $app_model->set_description($app_details_from_store["description"]);
        $app_model->set_device_id($device_id);
        $app_model->set_store_id($app_details_from_store["store_id"]);
        $app_created_id = AppsMapper::save($app_model);
        $redirect_link = link_to('/apps/edit/id/' . $app_created_id);
        $empty_app_code = json_encode(['code' => '']);
        CreateAppFile::make($_SESSION['d'], $app_created_id, $empty_app_code);
    else:
        $redirect_link = link_to('/apps/list/');
        $internal_message = 'Totally Error! App Doesn\'t Was Created';
    endif;


    echo <<<BOX
<div class='row' >
	<h2 >Success</h2 >
	<div class='large-12 columns' >
		<div class='panel radius' >
			App Downloaded Sueccesfully! And Added to Local App List.
		    <b>Internal Message:</b> $internal_message
		</div >
		<a class='button success small radius' href='http://store.uiot.org/panel/list/'>Go Back to Store</a >
		<a class='button success small radius' onclick='window.top.location.href="$redirect_link"'>Edit App</a >
	</div >
</div >
BOX;

    $_SESSION['d'] = null;
    $_SESSION['e'] = null;
    $app_details_from_store = null;
    $app_code_from_store = null;

elseif (isset($_GET['app_update'])):
    if (isset($_SESSION['d'])):
        $app_details_from_store = json_decode($_SESSION['d'], true);
        $app_model = new AppsModel();
        $app_model->set_public_name($app_details_from_store["name"]);
        $app_model->set_version($app_details_from_store["version"]);
        $app_model->set_author($app_details_from_store["author"]);
        $app_model->set_name($app_details_from_store["name"]);
        $app_model->set_description($app_details_from_store["description"]);
        $app_model->set_store_id($app_details_from_store['store_id']);
        AppsMapper::update_by_store_id($app_model, $app_details_from_store['store_id']);
        $get_app_content = AppsMapper::get_by_store($app_details_from_store['store_id']);
        $redirect_link = link_to('/apps/open/id/' . $get_app_content->get_pk_id());

        if ($app_details_from_store['is_author'] == true):
            $internal_message = 'Hello Developer, Your App Code will not be Updated. Since your Local Code is used for Development.';
        else:
            $internal_message = 'App Details Updated and Code Updated too.';
            SaveAppFile::make($get_app_content->get_pk_id(), $_SESSION['e']);
        endif;

        echo <<<BOX
<div class='row' >
	<h2 >Success</h2 >
	<div class='large-12 columns' >
		<div class='panel radius' >
			App Downloaded Sueccesfully! And Added to Local App List.
		    <b>Internal Message:</b> $internal_message
		</div >
		<a class='button success small radius' href='http://store.uiot.org/panel/list/'>Go Back to Store</a >
		<a class='button success small radius' onclick='window.top.location.href=\"$redirect_link\"'>View App</a >
	</div >
</div >
BOX;
        $_SESSION['d'] = null;
        $_SESSION['e'] = null;
        $app_details_from_store = null;
        $app_code_from_store = null;
    endif;
endif;

