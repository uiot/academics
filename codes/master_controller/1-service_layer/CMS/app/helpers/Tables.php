<?php


/**
 * Class Tables
 *
 * Manage and Create Tables
 *
 * $fields = array(
 * "name" => "title"
 * $content = array(
 * "name" => "value" // the same NAME as $fields
 *
 */
final
class Tables
{
    /**
     * Table HTML Content
     *
     * @var string
     */
    private static $table_content = '';

    /**
     * Create the Table
     *
     * @param array $fields Fields of the Table
     * @param array $content Content of the Table
     */
    public static
    function create($fields = [], $content = [])
    {
        self::$table_content .= "<table class='dynamic'>";
        self::build_header($fields);
        self::build_content($fields, $content);
        self::$table_content .= "</table><br class='spacer' /> ";
        echo self::$table_content;
        self::$table_content = '';
    }

    /**
     * Build the Header of the Table
     *
     * @param array $header_fields Fields of the Header
     */
    private static
    function build_header($header_fields = [])
    {
        self::$table_content .= "<thead><tr>";
        foreach ($header_fields as $key => $value)
            self::$table_content .= "<th>" . $value . "<i class='fa fa-sort'></i></th>";
        self::$table_content .= "</thead>";
    }

    /**
     * Build the Body of the Table
     *
     * @param array $body_fields Fields of the Body
     * @param array $body_values Values of the Body
     */
    private static
    function build_content($body_fields = [], $body_values = [])
    {
        self::$table_content .= "<tbody>";
        foreach ($body_values as $key => $row):
            self::$table_content .= "<tr>";
            foreach ($body_fields as $key => $body_values)
                self::$table_content .= "<td>" . $row[$key] . "</td>";
            self::$table_content .= "</tr>";
        endforeach;
        self::$table_content .= "</tbody>";
    }
}
