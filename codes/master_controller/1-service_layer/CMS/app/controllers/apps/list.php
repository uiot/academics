<?php

echo '<h2 > Available Apps </h2 ><style >#addstore { display: initial !important; }</style ><div id="apps_store"></div>';

$table_fields = ["open" => "Open", "name" => "App Name", "version" => "Version", "author" => "Author", "remove" => "Remove"];
$apps_object = AppsMapper::get_all();
$app_versions = [];

/* @var $apps_object AppsModel[] */
foreach ($apps_object as $app):

    $local_table_row = [
        "open" => "<a href='" . link_to("/apps/open/id/" . $app->get_pk_id()) . "'><i class='fa fa-location-arrow'></i></a>", "name" => "<b>" . $app->get_public_name() . "</b><br>[" . $app->get_pk_id() . "]", "version" => "<b>" . $app->get_version() . "</b><br>[" . $app->get_pk_id() . "]", "author" => "<b>" . $app->get_author() . "</b><br>[" . $app->get_pk_id() . "]", "remove" => "<a href='" . link_to("/apps/remove/id/" . $app->get_pk_id()) . "'><i class='fa fa-times-circle' style=' color:red;'></i></a>"
    ];

    $apps_versions[] = ['id' => $app->get_store_id(), 'name' => $app->get_public_name(), 'version' => $app->get_version()];
    $table_rows[] = $local_table_row;
endforeach;

Tables::create($table_fields, $table_rows);
$apps_versions = json_encode($apps_versions);

echo <<<SCRIPT
<script>
	$ (document).ready (function () {
		var apps = '$apps_versions';
		$ ("#apps_store").load ("http://store.uiot.org/response/check_apps/", {check: apps});
	});
</script>
SCRIPT;
