<?php

/**
 * Class Database_Adapter
 * Makes Helpers to Database
 * Controls the Querys Execution for the Database
 */
final
class DatabaseAdapter
{
    /**
     * @var
     * Handles the Connection
     */
    private static $conn = null;

    /**
     * @param $database
     * Opens the Database Connection
     */
    public static
    function create_connection($database)
    {
        if (!self::$conn):
            self::$conn = DatabaseConnector::open($database);
        endif;
    }

    /**
     * @return mixed
     * Gets the Database Instance and Returns
     */
    public static
    function get_connection()
    {
        return self::$conn;
    }

    /**
     * Closes the Database
     */
    public static
    function destroy_connection()
    {
        if (self::$conn):
            self::$conn = null;
        endif;
    }

    /**
     * @param null $query
     *
     * @return string
     *
     * Makes a Database Query
     */
    public static
    function query($query = null)
    {
        try {
            if (self::$conn):
                $result = self::$conn->query($query);
                if ($result):
                    return $result;
                endif;
            else:
                return false;
            endif;
        } catch (PDOException $exception) {
            trigger_error("PDO Error in Query: $query AND Exception: $exception", E_WARNING);
            Framework::uxdie("[database] exception: $exception");
        }
    }

    /**
     * @param null $result
     *
     * @return string
     *
     * Fetch in a Array the Query
     */
    public static
    function fetch_row($result = null)
    {
        try {
            if ($result):
                $row = $result->fetch(PDO::FETCH_ASSOC);
                return $row;
            else:
                return false;
            endif;
        } catch (PDOException $exception) {
            Framework::uxdie("[database] exception: $exception");
        }
    }

    /**
     * @param null $query
     *
     * @return string
     *
     * Counts the Numbers of Rows of a Query
     */
    public static
    function num_rows($query = null)
    {
        try {
            if ($query):
                $counts = $query->rowCount();
                return $counts;
            else:
                return 0;
            endif;
        } catch (PDOException $exception) {
            Framework::uxdie("[database] exception: $exception");
        }
    }

}

