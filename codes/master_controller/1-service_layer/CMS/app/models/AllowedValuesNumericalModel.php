<?php

final
class AllowedValuesNumericalModel
{

    private $_pk_id;
    private $_state_variable;
    private $_minimum_value;
    private $_maximum_value;
    private $_step;

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

    // Get and Set from minimum_value
    public
    function get_minimum_value()
    {
        return $this->_minimum_value;
    }

    public
    function set_minimum_value($value)
    {
        $this->_minimum_value = escape_text($value);
    }

    // Get and Set from maximum_value
    public
    function get_maximum_value()
    {
        return $this->_maximum_value;
    }

    public
    function set_maximum_value($value)
    {
        $this->_maximum_value = escape_text($value);
    }

    // Get and Set from step
    public
    function get_step()
    {
        return $this->_step;
    }

    public
    function set_step($value)
    {
        $this->_step = escape_text($value);
    }
}
