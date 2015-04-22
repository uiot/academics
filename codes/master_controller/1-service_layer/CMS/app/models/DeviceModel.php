<?php

final
class DeviceModel
{

    private $_pk_id;
    private $_udn;
    private $_slave_controller;
    private $_friendly_name;
    private $_device_type;
    private $_manufacturer;
    private $_manufacturer_url;
    private $_model_description;
    private $_model_name;
    private $_model_number;
    private $_model_url;
    private $_serial_number;
    private $_upc;
    private $_spec_version_major;
    private $_spec_version_minor;
    private $_xml_link;
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

    // Get and Set from udn
    public
    function get_udn()
    {
        return $this->_udn;
    }

    public
    function set_udn($value)
    {
        $this->_udn = escape_text($value);
    }

    // Get and Set from slave_controller
    public
    function get_slave_controller()
    {
        return $this->_slave_controller;
    }

    public
    function set_slave_controller($value)
    {
        $this->_slave_controller = escape_text($value);
    }

    // Get and Set from friendly_name
    public
    function get_friendly_name()
    {
        return $this->_friendly_name;
    }

    public
    function set_friendly_name($value)
    {
        $this->_friendly_name = escape_text($value);
    }

    // Get and Set from device_type
    public
    function get_device_type()
    {
        return $this->_device_type;
    }

    public
    function set_device_type($value)
    {
        $this->_device_type = escape_text($value);
    }

    // Get and Set from manufacturer
    public
    function get_manufacturer()
    {
        return $this->_manufacturer;
    }

    public
    function set_manufacturer($value)
    {
        $this->_manufacturer = escape_text($value);
    }

    // Get and Set from manufacturer_url
    public
    function get_manufacturer_url()
    {
        return $this->_manufacturer_url;
    }

    public
    function set_manufacturer_url($value)
    {
        $this->_manufacturer_url = escape_text($value);
    }

    // Get and Set from model_description
    public
    function get_model_description()
    {
        return $this->_model_description;
    }

    public
    function set_model_description($value)
    {
        $this->_model_description = escape_text($value);
    }

    // Get and Set from model_name
    public
    function get_model_name()
    {
        return $this->_model_name;
    }

    public
    function set_model_name($value)
    {
        $this->_model_name = escape_text($value);
    }

    // Get and Set from model_number
    public
    function get_model_number()
    {
        return $this->_model_number;
    }

    public
    function set_model_number($value)
    {
        $this->_model_number = escape_text($value);
    }

    // Get and Set from model_url
    public
    function get_model_url()
    {
        return $this->_model_url;
    }

    public
    function set_model_url($value)
    {
        $this->_model_url = escape_text($value);
    }

    // Get and Set from serial_number
    public
    function get_serial_number()
    {
        return $this->_serial_number;
    }

    public
    function set_serial_number($value)
    {
        $this->_serial_number = escape_text($value);
    }

    // Get and Set from upc
    public
    function get_upc()
    {
        return $this->_upc;
    }

    public
    function set_upc($value)
    {
        $this->_upc = escape_text($value);
    }

    // Get and Set from spec_version_major
    public
    function get_spec_version_major()
    {
        return $this->_spec_version_major;
    }

    public
    function set_spec_version_major($value)
    {
        $this->_spec_version_major = escape_text($value);
    }

    // Get and Set from spec_version_minor
    public
    function get_spec_version_minor()
    {
        return $this->_spec_version_minor;
    }

    public
    function set_spec_version_minor($value)
    {
        $this->_spec_version_minor = escape_text($value);
    }

    // Get and Set from xml_link
    public
    function get_xml_link()
    {
        return $this->_xml_link;
    }

    public
    function set_xml_link($value)
    {
        $this->_xml_link = escape_text($value);
    }

    // Get and Set from store_id
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
