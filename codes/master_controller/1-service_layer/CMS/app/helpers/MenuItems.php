<?php

/**
 * Class Menu
 * Creates <li> for the Menu
 */
final
class MenuItems
{
    /**
     * @var string
     */
    private $content = '';

    /**
     * Does Nothing
     */
    public
    function __construct()
    {

    }

    /**
     * @param string $url
     * @param string $div_class
     * @param string $div_id
     * @param string $text
     * @param string $ref_label
     */
    public
    function add_item($url = '', $div_class = '', $div_id = '', $text = '', $ref_label = '')
    {
        $this->content .= '<li class="' . $ref_label . '"><a href="' . $url . '" ><i class="' . $div_class . '" id="' . $div_id . '"></i > ' . $text . '</a ></li >';
    }

    /**
     * @param string $text
     * @param string $icon
     */
    public
    function add_label($text = '', $icon = '')
    {
        $this->content .= '<li class="heading"><i class="' . $icon . '"></i>  ' . $text . '</li>';
    }

    /**
     * Render the content
     */
    public
    function render_items()
    {
        echo $this->content;
    }

}



