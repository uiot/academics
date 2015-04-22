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

    $remove = ['BO_Deleted'];
    $table_fields = array_diff($table_fields, $remove);

    // Make the Class Name
    $class_array = explode("_", $db_table);
    $class_array = array_map(function ($word) {
        return ucfirst($word);
    }, $class_array);
    $class_name = implode("_", $class_array);
    $class_name = str_replace("_", "", $class_name);

    // Start Content
    $content
        = <<<CONTENT
<?php
final class {$class_name}Model {
CONTENT;
    $content = $content . "\r\n";
    foreach ($table_fields as $key => $column_name):
        $table_text = substr($column_name, 3);
        $table_text = strtolower($table_text);
        if ($column_name == 'PK_Id')
            $content = $content . "\r\n private \$_pk_id;";
        else
            $content = $content . "\r\n private \$_{$table_text};";
        $table_text = '';
    endforeach;
    $content = $content . "\r\n";
    foreach ($table_fields as $key => $column_name):
        $table_text = substr($column_name, 3);
        $table_text = strtolower($table_text);
        $content = $content . "\r\n // Get and Set from {$table_text} \r\n";
        if ($column_name == 'PK_Id')
            $content = $content . <<<CONTENT
public function get_pk_id(){
return \$this->_pk_id;
}

public function set_pk_id(\$value){
\$this->_pk_id = escape_text ( \$value );
}
CONTENT;
        else if ($column_name == 'TE_Password')
            $content = $content . <<<CONTENT
public function get_{$table_text}(){
return \$this->_{$table_text};
}

public function set_{$table_text}(\$value){
\$this->_{$table_text} = md5 ( \$value );
}
CONTENT;
        else
            $content = $content . <<<CONTENT
public function get_{$table_text}(){
return \$this->_{$table_text};
}

public function set_{$table_text}(\$value){
\$this->_{$table_text} = escape_text ( \$value );
}
CONTENT;
        $table_text = '';
    endforeach;
    $content = $content . "\r\n } \r\n";
    file_put_contents(ROOT_PATH . "/app/models/{$class_name}Model.php", $content);
    $content = '';
    $table_array = '';
    $table_text = '';
    $table_name = '';
    $table_fields = '';
    $class_name = '';
    $class_array = '';
    $db_table = '';
    $describe_query = '';
    $describe_row = '';
    $remove = '';
endforeach;

