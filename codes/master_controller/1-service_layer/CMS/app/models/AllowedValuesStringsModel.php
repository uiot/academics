<?php

final
class AllowedValuesStringsModel
{

    private $_pk_id;
    private $_state_variable;
    private $_value;

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

    // Get and Set from state_variable
    public
    function get_state_variable()
    {
        return $this->_state_variable;
    }

    public
    function set_state_variable($value)
    {
        $this->_state_variable = escape_text($value);
    }

    // Get and Set from value
    public
    function get_value()
    {
        return $this->_value;
    }

    public
    function set_value($value)
    {
        $this->_value = escape_text($value);
    }
}
