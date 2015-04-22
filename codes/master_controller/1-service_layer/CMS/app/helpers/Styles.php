<?php

/**
 * Class Stylesheet
 * Make Stylesheets Urls
 */
class Stylesheet
{
    /**
     * @var string
     */
    var $stylesheets_content = '';

    /**
     * Do Nothing
     */
    public
    function __construct()
    {

    }

    /**
     * Add a Style
     *
     * @param string $url
     */
    public
    function add($url = '')
    {
        $url = link_to($url);
        $this->stylesheets_content .= "<link rel='stylesheet' href='$url' type='text/css'/>" . "\n\r";
    }

    /**
     * Render the Content
     *
     * @return string
     */
    public
    function get_html()
    {
        return $this->stylesheets_content;
    }

}
