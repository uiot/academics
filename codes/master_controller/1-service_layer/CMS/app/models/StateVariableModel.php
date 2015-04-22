<?php

final
class StateVariableModel
{

    private $_pk_id;
    private $_service;
    private $_name;
    private $_send_events;
    private $_multicast;
    private $_data_type;
    private $_default_value;
    private $_reading_circuit_type;
    private $_writing_circuit_type;
    private $_reading_circuit_pin;
    private $_writing_circuit_pin;
    private $_reading_circuit_baudrate;
    private $_writing_circuit_baudrate;

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

    // Get and Set from send_events
    public
    function get_send_events()
    {
        return $this->_send_events;
    }

    public
    function set_send_events($value)
    {
        $this->_send_events = escape_text($value);
    }

    // Get and Set from multicast
    public
    function get_multicast()
    {
        return $this->_multicast;
    }

    public
    function set_multicast($value)
    {
        $this->_multicast = escape_text($value);
    }

    // Get and Set from data_type
    public
    function get_data_type()
    {
        return $this->_data_type;
    }

    public
    function set_data_type($value)
    {
        $this->_data_type = escape_text($value);
    }

    // Get and Set from default_value
    public
    function get_default_value()
    {
        return $this->_default_value;
    }

    public
    function set_default_value($value)
    {
        $this->_default_value = escape_text($value);
    }

    // Get and Set from reading_circuit_type
    public
    function get_reading_circuit_type()
    {
        return $this->_reading_circuit_type;
    }

    public
    function set_reading_circuit_type($value)
    {
        $this->_reading_circuit_type = escape_text($value);
    }

    // Get and Set from writing_circuit_type
    public
    function get_writing_circuit_type()
    {
        return $this->_writing_circuit_type;
    }

    public
    function set_writing_circuit_type($value)
    {
        $this->_writing_circuit_type = escape_text($value);
    }

    // Get and Set from reading_circuit_pin
    public
    function get_reading_circuit_pin()
    {
        return $this->_reading_circuit_pin;
    }

    public
    function set_reading_circuit_pin($value)
    {
        $this->_reading_circuit_pin = escape_text($value);
    }

    // Get and Set from writing_circuit_pin
    public
    function get_writing_circuit_pin()
    {
        return $this->_writing_circuit_pin;
    }

    public
    function set_writing_circuit_pin($value)
    {
        $this->_writing_circuit_pin = escape_text($value);
    }

    // Get and Set from reading_circuit_baudrate
    public
    function get_reading_circuit_baudrate()
    {
        return $this->_reading_circuit_baudrate;
    }

    public
    function set_reading_circuit_baudrate($value)
    {
        $this->_reading_circuit_baudrate = escape_text($value);
    }

    // Get and Set from writing_circuit_baudrate
    public
    function get_writing_circuit_baudrate()
    {
        return $this->_writing_circuit_baudrate;
    }

    public
    function set_writing_circuit_baudrate($value)
    {
        $this->_writing_circuit_baudrate = escape_text($value);
    }
}
