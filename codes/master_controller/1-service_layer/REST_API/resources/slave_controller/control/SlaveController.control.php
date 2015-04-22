<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/resources/slave_controller/db/SlaveController.db.php";
final class SlaveController {
	
	var $db_slave_controller;
	
	public function __construct() {
		self::start_db_controller();
	}

	public function execute_request($request, $request_type, $connection) {
		switch($request_type) {
			case 'GET':
				return self::execute_get_request($request,$connection);
			case 'POST':
				return self::execute_post_request($request,$connection);
			case 'PUT':
				return self::execute_put_request($request,$connection);
			case 'DELETE':
				return self::execute_delete_request($request,$connection);
			default:		
				return http_response(405); //code for not allowed method			
		}
	}

	public function execute_get_request($request, $connection) {		
		switch(self::number_of_params($request)) {
	   		case 1: 
				return self::select_all_slaves($connection);

	   		case 2:		
				return self::select_slave_by_unic_name($request[1], $connection);
		
	   		case 3:
				return self::choose_request($request[2], $request[1], $connection); 

	   		default:
				return http_response(405); //code for not allowed method			
		}
	
	}
	
	public function execute_post_request($request, $connection) {
	
	}
	
	public function execute_put_request($request, $connection) {
	
	}
	
	public function execute_delete_request($request, $connection) {
	
	} 
	
	private function start_db_controller() {
		$this->db_slave_controller = new DBSlaveController();	
	}

	private function choose_request($request, $slave_name, $connection) {
		if(strcmp($request,'devices') == 0) {				
			return $this->db_slave_controller->select_all_associated_devices($slave_name,$connection);
		}
 		return http_response(405);	
	}
	
	private function select_slave_by_unic_name($request, $connection) {
		return $this->db_slave_controller->select_slave_by_unic_name($request, $connection);
	}
	
	private function select_all_slaves($connection) {
		return $this->db_slave_controller->select_all_slaves($connection);
	}
	
	private function number_of_params($request) {
		return (sizeof($request) - 1);
	}
}

?>
