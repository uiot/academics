<?php

/**
 * Class Script
 * Creates Scripts
 */
class Script
{

    /**
     * @var string
     */
    var $scripts = '';

    /**
     * Does Nothing
     */
    public
    function __construct()
    {

    }

    /**
     * Add a Script
     *
     * @param string $url
     */
    public
    function add($url = '')
    {
        $url = link_to($url);
        $this->scripts .= "<script src='$url' type='text/javascript'></script>" . "\n\r";
    }

    /**
     * Return the Script Code
     *
     * @return string
     */
    public
    function get_html()
    {
        return $this->scripts;
    }

}
