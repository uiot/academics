<?php

final
class ConfigModel
{

    private $_cml_ip;
    private $_cml_port;
    private $_svl_ip;
    private $_svl_port;
    private $_cll_ip;
    private $_cll_port_in;
    private $_cll_port_out;
    private $_max_cpu;
    private $_max_mem;

    // Get and Set from cml_ip
    public
    function get_cml_ip()
    {
        return $this->_cml_ip;
    }

    public
    function set_cml_ip($value)
    {
        $this->_cml_ip = escape_text($value);
    }

    // Get and Set from cml_port
    public
    function get_cml_port()
    {
        return $this->_cml_port;
    }

    public
    function set_cml_port($value)
    {
        $this->_cml_port = escape_text($value);
    }

    // Get and Set from svl_ip
    public
    function get_svl_ip()
    {
        return $this->_svl_ip;
    }

    public
    function set_svl_ip($value)
    {
        $this->_svl_ip = escape_text($value);
    }

    // Get and Set from svl_port
    public
    function get_svl_port()
    {
        return $this->_svl_port;
    }

    public
    function set_svl_port($value)
    {
        $this->_svl_port = escape_text($value);
    }

    // Get and Set from cll_ip
    public
    function get_cll_ip()
    {
        return $this->_cll_ip;
    }

    public
    function set_cll_ip($value)
    {
        $this->_cll_ip = escape_text($value);
    }

    // Get and Set from cll_port_in
    public
    function get_cll_port_in()
    {
        return $this->_cll_port_in;
    }

    public
    function set_cll_port_in($value)
    {
        $this->_cll_port_in = escape_text($value);
    }

    // Get and Set from cll_port_out
    public
    function get_cll_port_out()
    {
        return $this->_cll_port_out;
    }

    public
    function set_cll_port_out($value)
    {
        $this->_cll_port_out = escape_text($value);
    }

    // Get and Set from max_cpu
    public
    function get_max_cpu()
    {
        return $this->_max_cpu;
    }

    public
    function set_max_cpu($value)
    {
        $this->_max_cpu = escape_text($value);
    }

    // Get and Set from max_mem
    public
    function get_max_mem()
    {
        return $this->_max_mem;
    }

    public
    function set_max_mem($value)
    {
        $this->_max_mem = escape_text($value);
    }
}
