<?php
if (isset($_GET['id'])):
    $app_id = escape_text($_GET['id']);
    if (is_numeric($app_id)):
        $app_details = AppsMapper::get_by_id($app_id);
        echo <<<BOX
<style>#Menu {  display: none !important;  }</style>
<div class='row'>
	<h2>Edit</h2>
	<div class='large-12 columns'>
		<div class='panel radius'>
			Hello, Do you Want to Edit the Following App?
			<br/>
			<b>Name:</b> {$app_details->get_public_name()}
			<br/>
			<b>Version:</b> {$app_details->get_version()}
		</div>
		<a class='button success small radius' href='/apps/editor/current_site/$app_id/'>Yes
			i Want to Edit</a>
		<a class='button success small radius' href='/apps/publish/id/$app_id'>No i Want
			to Publish</a>
		<a class='button success small radius' href='/apps/list/'>No, i want to go back to
			home.</a>
	</div>
</div>
BOX;
    else:
        redirect('/home/index/');
    endif;
else:
    redirect('/home/index/');
endif;