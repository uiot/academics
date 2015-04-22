<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/resources/state_variable/db/StateVariableController.db.php"; 

final class StateVariableController {

	var $db_state_var_controller;
	
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
				return self::select_all_state_vars($connection);

	   	case 2:		
				return self::select_state_var_by_id($request[1], $connection);
			
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
	
	private function select_all_state_vars($connection) {
		return $this->db_state_var_controller->select_all_state_vars($connection);
	}
	
	private function select_state_var_by_id($request, $connection) {
		return $this->db_state_var_controller->select_state_var_by_id($request, $connection);
	}
	
	private function start_db_controller() {
		$this->db_state_var_controller = new DBStateVarController();	
	}
	
	private function number_of_params($request) {
		return (sizeof($request) - 1);
	}
}

?>