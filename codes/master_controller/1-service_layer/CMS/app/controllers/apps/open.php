<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])):
    echo <<<SCRIPT
<div class="az-container container" data-az-type="builder" data-az-name="75"></div><script>setInterval(function(){ $('.azexo-editor').removeClass('azexo-editor');}, 300);</script>";
SCRIPT;
else:
    redirect('/home/index/');
endif;