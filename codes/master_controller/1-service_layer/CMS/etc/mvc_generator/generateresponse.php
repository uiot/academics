<?php
$tables = [
    'action', 'allowed_values_numerical', 'allowed_values_strings', 'apps', 'argument', 'config', 'device', 'service', 'slave_controller', 'state_variable', 'users'
];
foreach ($tables as $key => $value):
    // Get Table Name
    $db_table = $value;

    // Make Describe Query
    $describe_query = DatabaseAdapter::query("DESCRIBE $db_table");
    $table_fields = [];

    // Make the While
    while ($describe_row = DatabaseAdapter::fetch_row(($describe_query))):
        $table_fields[] = $describe_row['Field'];
    endwhile;

    $remove = ['PK_Id', 'BO_Deleted'];
    $table_fields = array_diff($table_fields, $remove);

    // Make the Class Name
    $class_array = explode("_", $db_table);
    $class_array = array_map(function ($word) {
        return ucfirst($word);
    }, $class_array);
    $class_namo = implode("_", $class_array);
    $class_name = str_replace("_", "", $class_namo);
    $class_message = str_replace("_", " ", $class_namo);

    /*
     * Add Response
     */
    $content_add
        = <<<CONTENT
<?php
if(isset(\$_POST['do'])):
/*
 * {$class_name} Response
 * Add Response
 */
CONTENT;

    $content_add = $content_add . <<<CONTENTS
\${$class_name} = new {$class_name}Model();
CONTENTS;
    foreach ($table_fields as $key => $column_name):
        $original_field = $column_name;
        $table_text = substr($column_name, 3);
        $table_text = strtolower($table_text);
        $content_add = $content_add . <<<CONTENT
\${$class_name}->set_{$table_text} (\$_POST["{$table_text}"]);
CONTENT;
        $table_text = '';
        $original_field = '';
    endforeach;
    $content_add = $content_add . <<<CONTENTS
{$class_name}Mapper::save ( \${$class_name} );
\$text['title']   = "Add {$class_message}";
\$text['message'] = "{$class_message} Added Successfully";
echo "<h2 >{\$text['title']}</h2 >
<div class='row' >
	<div class='large-12 columns' >
		<div class='panel radius' >
			{\$text['message']}
		</div >
		<a class='button success small radius' onclick='javascript: window.history.go(-2);' >Go Back</a >
	</div >
</div >";
endif;
CONTENTS;

    /*
     * Edit Response
     */
    $content_edit
        = <<<CONTENT
<?php
if(isset(\$_POST['do'])):
/*
 * {$class_name} Response
 * Edit Response
 */
CONTENT;
    $content_edit = $content_edit . <<<CONTENTS
\${$class_name} = new {$class_name}Model();
CONTENTS;
    foreach ($table_fields as $key => $column_name):
        $original_field = $column_name;
        $table_text = substr($column_name, 3);
        $table_text = strtolower($table_text);
        $content_edit = $content_edit . <<<CONTENT
\${$class_name}->set_{$table_text} (\$_POST["{$table_text}"]);
CONTENT;
        $table_text = '';
        $original_field = '';
    endforeach;
    $content_edit = $content_edit . <<<CONTENTS
	\$unique_id = \$_POST["id"];
{$class_name}Mapper::update ( \${$class_name} , \$unique_id);
\$text['title']   = "Edit {$class_message}";
\$text['message'] = "{$class_message} Edited Successfully";
echo "<h2 >{\$text['title']}</h2 >
<div class='row' >
	<div class='large-12 columns' >
		<div class='panel radius' >
			{\$text['message']}
		</div >
		<a class='button success small radius' onclick='javascript: window.history.go(-2);' >Go Back</a >
	</div >
</div >";
endif;
CONTENTS;

    /*
     * Remove Response
     */
    $content_remove
        = <<<CONTENT
<?php
if(isset(\$_POST['do'])):
/*
 * {$class_name} Response
 * Remove Response
 */
CONTENT;
    $content_remove = $content_remove . <<<CONTENTS
\${$class_name} = new {$class_name}Model();
CONTENTS;
    $content_remove = $content_remove . <<<CONTENTS
	\$unique_id = \$_POST["PK_Id"];
{$class_name}Mapper::remove (\$unique_id);
\$text['title']   = "Remove {$class_message}";
\$text['message'] = "{$class_message} Removed Successfully";
echo "<h2 >{\$text['title']}</h2 >
<div class='row' >
	<div class='large-12 columns' >
		<div class='panel radius' >
			{\$text['message']}
		</div >
		<a class='button success small radius' onclick='javascript: window.history.go(-2);' >Go Back</a >
	</div >
</div >";
endif;
CONTENTS;


    // Add
    $content_add = $content_add . file_get_contents(ROOT_PATH . "/app/controllers/{$class_name}/add.php");
    file_put_contents(ROOT_PATH . "/app/controllers/{$class_name}/add.php", $content_add);
    // Edit
    $content_edit = $content_edit . file_get_contents(ROOT_PATH . "/app/controllers/{$class_name}/edit.php");
    file_put_contents(ROOT_PATH . "/app/controllers/{$class_name}/edit.php", $content_edit);
    // Remove
    $content_remove = $content_remove . file_get_contents(ROOT_PATH . "/app/controllers/{$class_name}/remove.php");
    file_put_contents(ROOT_PATH . "/app/controllers/{$class_name}/remove.php", $content_remove);

    $content_add = '';
    $content_edit = '';
    $content_remove = '';
    $table_array = '';
    $table_text = '';
    $table_name = '';
    $table_fields = '';
    $class_name = '';
    $class_array = '';
    $db_table = '';
    $describe_query = '';
    $describe_row = '';
endforeach;