<?php

/**
 * Class ScriptCode
 * Makes Scripts with Codes
 */
class ScriptCode
{
    /**
     * @var string
     */
    var $scripts_codes_content;

    /**
     * Start the Tag of Script
     */
    public
    function __construct()
    {
        $this->scripts_codes_content = "<script type='text/javascript'>" . "\n\r";
    }

    /**
     * Add a Code in the Script
     *
     * @param string $code_content
     */
    public
    function add($code_content = '')
    {
        $this->scripts_codes_content .= $code_content;
    }

    /**
     * Render the Content
     *
     * @return string
     */
    public
    function get()
    {
        $this->scripts_codes_content .= "</script>";
        return $this->scripts_codes_content;
    }

}