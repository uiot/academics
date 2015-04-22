<?php

/**
 * Class Database_Connector
 * Makes the Connection to Database;
 */
final
class DatabaseConnector
{
    /**
     * @param $db
     *
     * @return PDO
     *
     * Opens the Database Connection
     */
    public static
    function open($db)
    {
        if (isset($db)):
            $user = isset($db['user']) ? $db['user'] : NULL;
            $pass = isset($db['pass']) ? $db['pass'] : NULL;
            $name = isset($db['name']) ? $db['name'] : NULL;
            $host = isset($db['host']) ? $db['host'] : NULL;
            $type = isset($db['type']) ? $db['type'] : NULL;
            $port = isset($db['port']) ? $db['port'] : NULL;
            try {
                switch ($type):
                    case 'pgsql':
                        $port = $port ? $port : '5432';
                        $conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass};host=$host;port={$port}", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                        break;
                    case 'mysql':
                        $port = $port ? $port : '3306';
                        if ($pass != NULL)
                            $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                        else
                            $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                        break;
                    case 'sqlite':
                        $conn = new PDO("sqlite:{$name}");
                        break;
                    case 'ibase':
                        $conn = new PDO("firebird:dbname={$name}", $user, $pass);
                        break;
                    case 'oci8':
                        $conn = new PDO("oci:dbname={$name}", $user, $pass);
                        break;
                    case 'mssql':
                        $conn = new PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                        break;
                endswitch;
            } catch (Exception $exception) {
                Framework::uxdie("[database] initializing exception $exception");
            }
            @$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $conn;
        endif;
    }
}
