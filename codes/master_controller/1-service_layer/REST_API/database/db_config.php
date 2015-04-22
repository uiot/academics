<?php

/**
* Database configurations
*/

DEFINE('DB_HOST','localhost');

DEFINE('DB_USER','uiot_admin');

DEFINE('DB_PASS','admin321');

DEFINE('DB_NAME','UIoT_Middleware');

DEFINE('DB_TYPE','mysql');

DEFINE('DB_PORT','');

$database = array("user"=>DB_USER,"pass"=>DB_PASS,"name"=>DB_NAME,"host"=>DB_HOST,"type"=>DB_TYPE,"port"=>DB_PORT);

/**
* Socket configurations
*/

DEFINE('SCKT_PORT', '');
DEFINE('SCKT_ADDRESS','');

?>
