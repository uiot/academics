<?php

final
class UsersModel
{

    private $_pk_id;
    private $_name;
    private $_username;
    private $_password;

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

    // Get and Set from username
    public
    function get_username()
    {
        return $this->_username;
    }

    public
    function set_username($value)
    {
        $this->_username = escape_text($value);
    }

    // Get and Set from password
    public
    function get_password()
    {
        return $this->_password;
    }

    public
    function set_password($value)
    {
        $this->_password = md5($value);
    }
}
