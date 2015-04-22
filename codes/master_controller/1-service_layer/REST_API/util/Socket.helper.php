<?php

class Socket {
	
	private $socket;
	private $port;
	private $address;

	public function __construct ($port, $address) {
		$this->port    = ($port == null) ? "" : $port; 
		$this->address = ($address == null) ? "" : $address; 
		$this->init_socket();
	}

	
	private function init_socket () {
		$return_code = "OK";
			$this->socket = socket_create (AF_INET, SOCK_STREAM, SOL_TCP);
			if ($this->socket === false)
				$return_code = "Failed to create socket. Reason: " . socket_strerror ( socket_last_error () ) . "\n";
		return $return_code;
	}

	
	public function send_data ($data) {
		$init = $this->init_socket ();
		if ($init == "OK"):
			$init = $this->connect_socket();
			if ($init == "OK"):
				$this->write ( $data );
				$return = $this->read ();
				$this->disconnect_socket ();
				return $return;
			else:
				return '{"status":"fail","message":"' . str_replace ( '"', '\\"', $init ) . '"}';
			endif;
		else:
			return '{"status":"fail","message":"' . $init . '"}';
		endif;
	}


	private function connect_socket() {
		$result = socket_connect ( $this->socket, $this->address, $this->port );
		if ($result === false)
			return "Failed to connect socket. Reason: " . socket_strerror ( socket_last_error ( $this->socket ) ) . "\n";
		else
			return "OK";
	}


	private function write($data) {
		socket_write ( $this->socket, $data, strlen($data) + 1);
	}

	private function read () {
		$return = "";
		while ($next = socket_read ( $this->socket, 2048 ))
			$return .= $next;
		return $return;
	}

	private function disconnect_socket () {
		socket_close ( $this->socket );
	}
}
