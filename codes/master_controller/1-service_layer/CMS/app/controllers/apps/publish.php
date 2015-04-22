<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])):
    $app_id = escape_text($_GET['id']);
    $app_mapper = AppsMapper::get_by_id($app_id);
    $publish_id = $app_mapper->get_store_id();
    $publish_code_default = GetAppFile::make($app_id);
    $publish_code_obfuscated = urlencode(base64_encode($publish_code_default));
    $publish_code = json_encode(['code' => $publish_code_obfuscated]);
    echo "<form action='http://store.uiot.org/response/publisha/' method='POST' id='send_json'><input type='hidden' name='app_code' value='$publish_code' /><input type='hidden' name='app_id' value='$publish_id' /></form><script> $('#send_json').submit();</script>";
else:
    redirect('/home/index/');
endif;
