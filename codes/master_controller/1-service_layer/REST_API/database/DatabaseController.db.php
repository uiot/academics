<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/properties/Database.properties.php";

final class DatabaseController {

	var $user;
	var $pass;
	var $name;
	var $host;
	var $type;
	var $port;
	
	
	public function __construct($database) {
			self::set_user($database['user']);
			self::set_pass($database['pass']);
			self::set_name($database['name']);
			self::set_host($database['host']);
			self::set_type($database['type']);
			self::set_port($database['port']);
	}	
	
	public function set_user($user) {
		$this->user = (isset($user) ? $user : NULL);	
	}
	
	public function set_pass($pass) {
		$this->pass = (isset($pass) ? $pass : NULL);
	}
	
	public function set_name($name) {
		$this->name = (isset($name) ? $name : NULL);
	}
	
	public function set_host($host) {
		$this->host = (isset($host) ? $host : NULL);
	}
	
	public function set_type($type) {
		$this->type = (isset($type) ? $type : NULL);
	}
	
	public function set_port($port) {
		$this->port = (isset($port) ? $port : NULL);
	}
	
	public function get_user() {
		return $this->user;
	}
	
	public function get_pass() {
		return $this->pass;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_host() {
		return $this->host;
	}
	
	public function get_type() {
		return $this->type;
	}
	
	public function get_port() {
		return $this->port;
	}

	
	public function get_PDO_object() {
			try {
				switch (self::get_type()):
					case 'pgsql':
						$conn = self::get_pgsql_connection();
						break;
					case 'mysql':
						$conn = self::get_mysql_connection();
						break;
					case 'sqlite':
						$conn = self::get_sqlite_connection();
						break;
					case 'ibase':
						$conn = self::get_ibase_connection();
						break;
					case 'oci8':
						$conn = self::get_oci8_connection();
						break;
					case 'mssql':
						$conn = self::get_mssql_connection();
						break;
				endswitch;
			} 
			catch (Exception $exception) {
				 return $exception->getMessage();
			}
			return $conn;
	}
	
	private function get_mysql_connection() {
		self::set_port('3306');
		$conn = new PDO( "mysql:host={$this->host};
								port={$this->port};
								dbname={$this->name}", 
									self::get_user(), 
								self::get_pass(), 
								array (PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
		
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;	
	}	
	
	private function get_pgsql_connection() {
		self::set_port('5432');
		$conn = new PDO( "pgsql:dbname={$this->name}; 
								user={$this->user}; 
								password={$this->pass};
								host=$this->host;
								port={$this->port}", 
								array (PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
								
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;		
	} 
	
	private function get_sqlite_connection() {
		$conn = new PDO( "sqlite:{$this->name}" );
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;	
	}
	
	private function get_ibase_connection() {
		$conn = new PDO( "firebird:dbname={$this->name}", 
								self::get_user(), 
								self::get_pass());
							   
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;		
	}
	
	private function get_oci8_connection() {
		$conn = new PDO( "oci:dbname={$this->name}", 
								self::get_user(), 
								self::get_pass());
								
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;
	}
	
	private function get_mssql_connection() {
	 	$conn = new PDO( "mssql:host={$this->host},
	 							1433;dbname={$this->name}", 
	 							self::get_user(), 
								self::get_pass());
	 								
	 	$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );		
		return $conn;
	}
	

}


?>