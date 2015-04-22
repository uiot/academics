<?php

final
class ActionModel
{

    private $_pk_id;
    private $_service;
    private $_name;

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

    // Get and Set from service
    public
    function get_service()
    {
        return $this->_service;
    }

    public
    function set_service($value)
    {
        $this->_service = escape_text($value);
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
}
