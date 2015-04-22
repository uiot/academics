<?php

/**
 * Class Socket_Communication
 */
class SocketCommunication
{
    /**
     * @var resource
     */
    private $socket;
    private $port;
    private $address;

    /**
     * @param null $port
     * @param null $address
     */
    public
    function __construct($port = null, $address = null)
    {
        $this->port = ($port == null) ? Framework::$app["LAYER"]["cll_port_in"] : $port;
        $this->address = ($address == null) ? Framework::$app["LAYER"]["cll_ip"] : $address;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $this->init_socket();
    }

    /**
     * @return string
     */
    private
    function init_socket()
    {
        $return_code = "OK";
        if ($this->socket === false):
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($this->socket === false)
                $return_code = "Failed to create socket. Reason: " . socket_strerror(socket_last_error()) . "\n";
        endif;
        return $return_code;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public
    function send_data($data)
    {
        $init = $this->init_socket();
        if ($init == "OK"):
            $init = $this->connect_socket();
            if ($init == "OK"):
                $this->write($data);
                $return = $this->read();
                $this->disconnect_socket();
                return $return;
            else:
                return '{"status":"fail","message":"' . str_replace('"', '\\"', $init) . '"}';
            endif;
        else:
            return '{"status":"fail","message":"' . $init . '"}';
        endif;
    }

    /**
     * @return string
     */
    private
    function connect_socket()
    {
        $result = socket_connect($this->socket, $this->address, $this->port);
        if ($result === false)
            return "Failed to connect socket. Reason: " . socket_strerror(socket_last_error($this->socket)) . "\n";
        else
            return "OK";
    }

    /**
     * @param $data
     */
    private
    function write($data)
    {
        socket_write($this->socket, $data, strlen($data));
    }

    /**
     * @return string
     */
    private
    function read()
    {
        $return = "";
        while ($next = socket_read($this->socket, 2048))
            $return .= $next;
        return $return;
    }

    /**
     *
     */
    private
    function disconnect_socket()
    {
        socket_close($this->socket);
    }
}
