<?php

final
class ServiceModel
{

    private $_pk_id;
    private $_device;
    private $_friendly_name;
    private $_description;
    private $_service_type;
    private $_service_id;
    private $_scpdurl;
    private $_control_url;
    private $_event_suburl;
    private $_spec_version_major;
    private $_spec_version_minor;
    private $_xml_link;

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

    // Get and Set from device
    public
    function get_device()
    {
        return $this->_device;
    }

    public
    function set_device($value)
    {
        $this->_device = escape_text($value);
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

    // Get and Set from service_type
    public
    function get_service_type()
    {
        return $this->_service_type;
    }

    public
    function set_service_type($value)
    {
        $this->_service_type = escape_text($value);
    }

    // Get and Set from service_id
    public
    function get_service_id()
    {
        return $this->_service_id;
    }

    public
    function set_service_id($value)
    {
        $this->_service_id = escape_text($value);
    }

    // Get and Set from scpdurl
    public
    function get_scpdurl()
    {
        return $this->_scpdurl;
    }

    public
    function set_scpdurl($value)
    {
        $this->_scpdurl = escape_text($value);
    }

    // Get and Set from control_url
    public
    function get_control_url()
    {
        return $this->_control_url;
    }

    public
    function set_control_url($value)
    {
        $this->_control_url = escape_text($value);
    }

    // Get and Set from event_suburl
    public
    function get_event_suburl()
    {
        return $this->_event_suburl;
    }

    public
    function set_event_suburl($value)
    {
        $this->_event_suburl = escape_text($value);
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
}
