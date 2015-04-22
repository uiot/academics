<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/resources/service/db/ServiceController.db.php"; 

final class ServiceController {
	
	var $db_service_controller;
	
	public function __construct() {
		self::start_db_controller();
	}
	
	private function start_db_controller() {
		$this->db_service_controller = new DBServiceController();	
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
				return self::select_all_services($connection);

	   	case 2:		
				return self::select_service_by_id($request[1], $connection);
		
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
	
	private function choose_request($request, $service_id, $connection) {
		if(strcmp($request,'actions') == 0) {				
				return $this->db_service_controller->select_associated_actions($service_id,$connection);
		} elseif(strcmp($request,'state_vars') == 0) {
				return $this->db_service_controller->select_service_state_variables($service_id,$connection);
		}
 		return http_response(405);	
	}
	
	private function select_all_services($connection) {
		return $this->db_service_controller->select_all_services($connection);
	}
	
	private function select_service_by_id($request,$connection) {
		return $this->db_service_controller->select_service_by_id($request,$connection);	
	}
	
	private function number_of_params($request) {
		return (sizeof($request) - 1);
	}
}

?>