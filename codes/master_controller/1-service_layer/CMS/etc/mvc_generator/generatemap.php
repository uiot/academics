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
    $len = sizeof($table_fields);

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
final class {$class_name}Mapper {
CONTENT;
    $content = $content . "\r\n";
    if ($db_table == 'config'):
        $content = $content . <<<CONTENT
public static function get_all()
{
\$db_row = DatabaseAdapter::fetch_row ( DatabaseAdapter::query( "SELECT * FROM {$db_table}" ) );
			\$model = new {$class_name}Model();
CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($column_name == 'PK_Id')
                $content = $content . <<<CONTENT
\$model->set_pk_id (\$db_row["{$original_field}"]);
CONTENT;
            else
                $content = $content . <<<CONTENT
\$model->set_{$table_text} (\$db_row["{$original_field}"]);
CONTENT;
            $table_text = '';
            $original_field = '';
        endforeach;
        $content = $content . "\r\n \${$db_table}_array = \$model; \r\n";
        $content = $content . "\r\n return \${$db_table}_array; \r\n";
        $content = $content . "\r\n } \r\n";
    else:
        $content = $content . <<<CONTENT
public static function get_all()
{
\${$db_table}_array = [];
\${$db_table}_query = DatabaseAdapter::query( "SELECT * FROM {$db_table} WHERE BO_Deleted = '0' " );
while (\$db_row = DatabaseAdapter::fetch_row ( \${$db_table}_query )):
			\$model = new {$class_name}Model();
CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($column_name == 'PK_Id')
                $content = $content . <<<CONTENT
\$model->set_pk_id (\$db_row["{$original_field}"]);
CONTENT;
            else
                $content = $content . <<<CONTENT
\$model->set_{$table_text} (\$db_row["{$original_field}"]);
CONTENT;
            $table_text = '';
            $original_field = '';
        endforeach;
        $content = $content . "\r\n \${$db_table}_array[] = \$model; \r\n";
        $content = $content . "\r\n endwhile; ";
        $content = $content . "\r\n return \${$db_table}_array; \r\n";
        $content = $content . "\r\n } \r\n";
        $content = $content . <<<CONTENT
public static
	function get_by_id ( \$item_id = null )
	{
	\$item_id = escape_text (\$item_id);
	\${$db_table}_query = DatabaseAdapter::query ( "
												SELECT * FROM {$db_table}
												WHERE
													BO_Deleted = '0'
												AND
													PK_Id      = '\$item_id'
												LIMIT 1" )
		;
		\${$db_table}_row   = DatabaseAdapter::fetch_row ( \${$db_table}_query );
		\$model                     = new {$class_name}Model();
CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($column_name == 'PK_Id')
                $content = $content . <<<CONTENT
\$model->set_pk_id (\${$db_table}_row["{$original_field}"]);
CONTENT;
            else
                $content = $content . <<<CONTENT
\$model->set_{$table_text} (\${$db_table}_row["{$original_field}"]);
CONTENT;
            $table_text = '';
            $original_field = '';
        endforeach;
        $content = $content . "return \$model; \r\n";
        $content = $content . "\r\n } \r\n";

        switch ($db_table):
            case 'action':
            case 'argument':
            case 'state_variable':
                $type = 'TE_Name';
                break;
            case 'service':
            case 'device':
                $type = 'TE_Friendly_Name';
                break;
            case 'users':
                $type = 'TE_Username';
                break;
            case 'slave_controller':
                $type = 'PK_Unic_Name';
                break;
            case 'apps':
                $type = 'TE_Public_Name';
                break;
            case 'allowed_values_numerical':
            case 'allowed_values_strings':
                $type = 'TE_Value';
                break;
        endswitch;
        $content = $content . <<<CONTENT
public static
	function get_by_name ( \$item_name = null )
	{
	\${$db_table}_array = [];
	\$item_name = escape_text(\$item_name);
\${$db_table}_query = DatabaseAdapter::query( "SELECT * FROM {$db_table} WHERE {$type} LIKE '%\$item_name%' AND BO_Deleted = '0' " );
while (\$db_row = DatabaseAdapter::fetch_row ( \${$db_table}_query )):
			\$model = new {$class_name}Model();
CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($column_name == 'PK_Id')
                $content = $content . <<<CONTENT
\$model->set_pk_id (\$db_row["{$original_field}"]);
CONTENT;
            else
                $content = $content . <<<CONTENT
\$model->set_{$table_text} (\$db_row["{$original_field}"]);
CONTENT;
            $table_text = '';
            $original_field = '';
        endforeach;
        $content = $content . "\r\n \${$db_table}_array[] = \$model; \r\n";
        $content = $content . "\r\n endwhile; ";
        $content = $content . "\r\n return \${$db_table}_array; \r\n";
        $content = $content . "\r\n } \r\n";

        $content = $content . <<<CONTENT
public static
	function save ( {$class_name}Model \$model )
	{
DatabaseAdapter::query ( "
					INSERT INTO {$db_table}
					(
CONTENT;
        $i = 0;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($i == (sizeof($table_fields) - 1))
                $content = $content . "{$original_field}";
            else
                $content = $content . "{$original_field},";
            $table_text = '';
            $original_field = '';
            $i++;
        endforeach;
        $i = 0;
        $content = $content . <<<CONTENT
)
VALUES
						(

CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($i == (sizeof($table_fields) - 1))
                $content = $content . "'{\$model->get_{$table_text} ()}'";
            else
                $content = $content . "'{\$model->get_{$table_text} ()}',";
            $table_text = '';
            $original_field = '';
            $i++;
        endforeach;
        $i = 0;
        $content = $content . <<<CONTENT
)" )
		;
CONTENT;
        if ($db_table == 'device'):
            $content = $content . <<<CONTENT
            \$db_instance = DatabaseAdapter::get_connection ();
			\$device_id =  \$db_instance->lastInsertId ();
			 XmlHandler::build_device_xml ( \$device_id );
			\$xml = ROOT_PATH . "/etc/xml/devices/{\$device_id}.xml";
			\$udn = uuid_format ( \$device_id );
			DatabaseAdapter::query ( "UPDATE Device SET TE_UDN ='\$udn' ,TE_XML_Link='\$xml' WHERE PK_Id=\$device_id " );
CONTENT;
        endif;
        if ($db_table == 'service'):
            $content = $content . <<<COLLO
		\$device_id = \$model->get_device ();
		 \$db_instance = DatabaseAdapter::get_connection ();
         \$service_id  = \$db_instance->lastInsertId ();
			\$product_url = "../services/{\$service_id}.xml";
			\$control_url        = "/device_{\$device_id}/service_{\$service_id}/control";
			\$event_url       = "/device_{\$device_id}/service_{\$service_id}/event";
			\$service_od  = \$service_id;
			DatabaseAdapter::query ( "UPDATE Service SET TE_XML_Link='\$product_url' ,TE_SCPDURL='\$product_url',TE_Service_Id='\$service_od', TE_Control_URL='\$control_url',TE_Event_SubURL='\$event_url' WHERE PK_Id=\$service_id " );
			XmlHandler::build_device_xml ( \$device_id );
			XmlHandler::build_service_xml ( \$service_id );
COLLO;
        endif;
        if ($db_table == 'action' || $db_table == 'argument' || $db_table == 'state_variable'):
            $content = $content . <<<CEEE
		\$service_id = \$model->get_service ();
XmlHandler::build_service_xml ( \$service_id );

CEEE;
        endif;
        $content = $content . '}';
    endif;
    if ($db_table == 'config'):
        $content = $content . <<<CONTENT
public static
	function update ( {$class_name}Model \$model )
	{
	DatabaseAdapter::query ( "
					UPDATE {$db_table}
					SET

CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($i == (sizeof($table_fields) - 1)):
                $content = $content . <<<CONTENT
{$original_field} = '{\$model->get_{$table_text} ()}'
CONTENT;
            else:
                $content = $content . <<<CONTENT
{$original_field} = '{\$model->get_{$table_text} ()}',
CONTENT;
            endif;
            $table_text = '';
            $original_field = '';
            $i++;
        endforeach;
        $i = 0;
        $content = $content . <<<CONTENT
" )
		;
		}
CONTENT;
    else:
        $content = $content . <<<CONTENT
public static
	function update ( {$class_name}Model \$model, \${$db_table}_id = null )
	{
	DatabaseAdapter::query ( "
					UPDATE {$db_table}
					SET

CONTENT;
        foreach ($table_fields as $key => $column_name):
            $original_field = $column_name;
            $table_text = substr($column_name, 3);
            $table_text = strtolower($table_text);
            if ($i == (sizeof($table_fields) - 1)):
                $content = $content . <<<CONTENT
{$original_field} = '{\$model->get_{$table_text} ()}'
CONTENT;
            else:
                $content = $content . <<<CONTENT
{$original_field} = '{\$model->get_{$table_text} ()}',
CONTENT;
            endif;
            $table_text = '';
            $original_field = '';
            $i++;
        endforeach;
        $i = 0;
        $content = $content . <<<CONTENT
WHERE
						PK_Id   = '\${$db_table}_id'
						AND
						BO_Deleted     = '0';" )
		;
CONTENT;
        if ($db_table == 'device'):
            $content = $content . <<<CONTET
		XmlHandler::build_device_xml ( \${$db_table}_id );
CONTET;
        endif;
        if ($db_table == 'service'):
            $content = $content . <<<EFFFE
	\$device_id = \$model->get_device ();
XmlHandler::build_service_xml ( \${$db_table}_id );
			XmlHandler::build_device_xml ( \$device_id );
EFFFE;
        endif;
        if ($db_table == 'action' || $db_table == 'argument' || $db_table == 'state_variable'):
            $content = $content . <<<EHEE
\$service_id = \$model->get_service ();
XmlHandler::build_service_xml ( \$service_id );
EHEE;
        endif;
        $content = $content . "\r\n } \r\n";
        $content = $content . <<<CONTENT
public static
	function remove ( \${$db_table}_id = null )
	{
DatabaseAdapter::query ( "
					UPDATE {$db_table}
					SET
						BO_Deleted   = '1'
					WHERE
						PK_Id = '\${$db_table}_id'" )
		;
CONTENT;
        if ($db_table == 'device'):
            $content = $content . <<<LELE
\$service_query = DatabaseAdapter::query ( "SELECT PK_Id FROM service WHERE FK_Device=\${$db_table}_id AND BO_Deleted='0'" );
		while (\$service_row = DatabaseAdapter::fetch_row ( \$service_query ))
		{
			\$service_id = \$service_row["PK_Id"];
			DatabaseAdapter::query ( "UPDATE service SET BO_Deleted='1' WHERE PK_Id=\$service_id" );
			DatabaseAdapter::query ( "UPDATE state_variable SET BO_Deleted='1' WHERE FK_Service=\$service_id" );
			DatabaseAdapter::query ( "UPDATE action SET BO_Deleted='1' WHERE FK_Service=\$service_id" );
			unlink ( ROOT_PATH . "/etc/xml/services/" . \$service_id . ".xml" );
		}
		unlink ( ROOT_PATH . "/etc/xml/services/" . \${$db_table}_id . ".xml" );
LELE;
        endif;
        if ($db_table == 'slave_controller'):
            $content = $content . <<<KAAKAK
\$query = DatabaseAdapter::query ( "SELECT PK_Id FROM device WHERE FK_Slave_Controller=\${$db_table}_id AND BO_Deleted='0'" );
				while (\$device = DatabaseAdapter::fetch_row ( \$query )):
					\$device_id = \$device["PK_Id"];
					DatabaseAdapter::query ( "UPDATE device SET BO_Deleted='1' WHERE FK_Id=\$device_id" );
					\$query2 = DatabaseAdapter::query ( "SELECT PK_Id FROM service WHERE FK_Device=\$device_id AND BO_Deleted='0'" );
					while (\$count = DatabaseAdapter::fetch_row ( \$query2 )):
						\$service_id = \$count["PK_Id"];
						DatabaseAdapter::query ( "UPDATE service SET BO_Deleted='1' WHERE PK_Id=\$service_id" );
						DatabaseAdapter::query ( "UPDATE state_variable SET BO_Deleted='1' WHERE FK_Service=\$service_id" );
						DatabaseAdapter::query ( "UPDATE action SET BO_Deleted='1' WHERE FK_Service=\$service_id" );
						unlink ( ROOT_PATH . "/etc/xml/services/" . \$service_id . ".xml" );
					endwhile;
					unlink ( ROOT_PATH . "/etc/xml/services/" . \$device_id . ".xml" );
				endwhile;
KAAKAK;


        endif;
        if ($db_table == 'service'):
            $content = $content . <<<KKKK
XmlHandler::build_device_xml ( \${$db_table}_id );
KKKK;
        endif;
        if ($db_table == 'action'):
            $content = $content . <<<EHUJDKD
		\$query = DatabaseAdapter::fetch_row ( DatabaseAdapter::query ( "SELECT * FROM action WHERE PK_Id='\${$db_table}_id' AND BO_Deleted='0'" ) );
DatabaseAdapter::query ( "UPDATE argument SET BO_Deleted='1' WHERE FK_Action='\${$db_table}_id'" );
				XmlHandler::build_service_xml ( \$query["FK_Service"] );
EHUJDKD;
        endif;
        if ($db_table == 'state_variable'):
            $content = $content . <<<EHUJDKD
\$query    = DatabaseAdapter::fetch_row ( DatabaseAdapter::query ( "SELECT FK_Service FROM state_variable  WHERE  PK_Id = \${$db_table}_id AND BO_Deleted='0'" ) );
DatabaseAdapter::query ( "UPDATE argument SET BO_Deleted='1' WHERE FK_Related_State_Variable='\${$db_table}_id'" );
				XmlHandler::build_service_xml ( \$query["FK_Service"] );
EHUJDKD;
        endif;
        if ($db_table == 'argument'):
            $content = $content . <<<IHGKJT
		\$query    = DatabaseAdapter::fetch_row ( DatabaseAdapter::query ( "SELECT * FROM action WHERE PK_Id IN (SELECT FK_Action FROM Argument WHERE PK_Id=\${$db_table}_id AND BO_Deleted=0)" ) );
XmlHandler::build_service_xml ( \$query["FK_Service"] );
IHGKJT;

        endif;
        $content = $content . "\r\n } \r\n";
        if ($db_table == 'users'):
            $content = $content . <<<JJJJJ
public static
	function authenticate ( \$username = '', \$password = '' )
	{
		if (DatabaseAdapter::num_rows ( DatabaseAdapter::query ( "SELECT 1 FROM users WHERE TE_Username = '\$username'" ) ) == 1)
			return DatabaseAdapter::fetch_row ( DatabaseAdapter::query ( "SELECT * FROM users WHERE TE_Username = '\$username' AND TE_Password = MD5('\$password')" ) );
		else
			return 'username';
	}
JJJJJ;

        endif;
    endif;
    $content = $content . "\r\n } \r\n";
    file_put_contents(ROOT_PATH . "/app/mappers/{$class_name}Mapper.php", $content);
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
endforeach;