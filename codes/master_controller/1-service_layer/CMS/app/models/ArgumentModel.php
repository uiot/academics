<?php

final
class ArgumentModel
{

    private $_pk_id;
    private $_action;
    private $_name;
    private $_direction;
    private $_ret_val;
    private $_related_state_variable;
    private $_service;

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

    // Get and Set from action

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

    // Get and Set from name

    public
    function get_direction()
    {
        return $this->_direction;
    }

    public
    function set_direction($value)
    {
        $this->_direction = escape_text($value);
    }

    // Get and Set from direction

    public
    function get_ret_val()
    {
        return $this->_ret_val;
    }

    public
    function set_ret_val($value)
    {
        $this->_ret_val = escape_text($value);
    }

    // Get and Set from ret_val

    public
    function get_related_state_variable()
    {
        return $this->_related_state_variable;
    }

    public
    function set_related_state_variable($value)
    {
        $this->_related_state_variable = escape_text($value);
    }

    // Get and Set from related_state_variable

    public
    function get_service()
    {
        if (!isset($this->_service)) {
            $this->_service = ActionMapper::get_by_id($this->get_action())->get_service();
        }
        return $this->_service;
    }

    public
    function get_action()
    {
        return $this->_action;
    }

    public
    function set_action($value)
    {
        $this->_action = escape_text($value);
    }
}
