<?php

final
class SlaveControllerModel
{

    private $_unic_name;
    private $_type;
    private $_address;
    private $_description;

    // Get and Set from unic_name
    public
    function get_unic_name()
    {
        return $this->_unic_name;
    }

    public
    function set_unic_name($value)
    {
        $this->_unic_name = escape_text($value);
    }

    // Get and Set from type
    public
    function get_type()
    {
        return $this->_type;
    }

    public
    function set_type($value)
    {
        $this->_type = escape_text($value);
    }

    // Get and Set from address
    public
    function get_address()
    {
        return $this->_address;
    }

    public
    function set_address($value)
    {
        $this->_address = escape_text($value);
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
}
