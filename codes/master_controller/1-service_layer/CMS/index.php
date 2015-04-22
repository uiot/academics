<?php
/**
 * If .htaccess is Not Configured
 * Display This Error
 */

include('app/helpers/HttpHeaders.php');
$status_error = new HttpHeaders();
$error_code = $status_error->render_status(500, 'The .htaccess File Doesnt Exists or this HTTP Server Doesnt Support Him');
echo $error_code;
