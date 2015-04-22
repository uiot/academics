<?php

final
class AppsModel
{

    private $_pk_id;
    private $_public_name;
    private $_version;
    private $_author;
    private $_name;
    private $_description;
    private $_device_id;
    private $_store_id;

    // Get and Set from id
    public
    function get_pk_id()
    {
        return $this->_pk_id;
    }

    public
    function set_pk_id($value)
    {
        $this->_pk_id = escape_text($value);
    }

    // Get and Set from public_name
    public
    function get_public_name()
    {
        return $this->_public_name;
    }

    public
    function set_public_name($value)
    {
        $this->_public_name = escape_text($value);
    }

    // Get and Set from version
    public
    function get_version()
    {
        return $this->_version;
    }

    public
    function set_version($value)
    {
        $this->_version = escape_text($value);
    }

    // Get and Set from author
    public
    function get_author()
    {
        return $this->_author;
    }

    public
    function set_author($value)
    {
        $this->_author = escape_text($value);
    }

    // Get and Set from name
    public
    function get_name()
    {
        return $this->_name;
    }

    public
    function set_name($value)
    {
        $this->_name = escape_text($value);
    }

    // Get and Set from description
    public
    function get_description()
    {
        return $this->_description;
    }

    public
    function set_description($value)
    {
        $this->_description = escape_text($value);
    }

    // Get and Set from Device Id
    public
    function get_device_id()
    {
        return $this->_device_id;
    }

    public
    function set_device_id($value)
    {
        $this->_device_id = escape_text($value);
    }

    // Get and Set from Store Id
    public
    function get_store_id()
    {
        return $this->_store_id;
    }

    public
    function set_store_id($value)
    {
        $this->_store_id = escape_text($value);
    }

}
