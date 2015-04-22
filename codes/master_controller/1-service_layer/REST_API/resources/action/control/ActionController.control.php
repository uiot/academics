<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/resources/action/db/ActionController.db.php"; 	

final class ActionController {
	
	var $db_action_controller;
	
	public function __construct() {
		self::start_db_controller();
	}
	
	private function start_db_controller() {
		$this->db_action_controller = new DBActionController();	
	}
	
	public function execute_request($request, $parameters, $request_type, $connection) {
		switch($request_type) {
			case 'GET':
				return self::execute_get_request($request,$connection);
			case 'POST':
				return self::execute_post_request($request,$connection);
			case 'PUT':
				return self::execute_put_request($request,$parameters,$connection);
			case 'DELETE':
				return self::execute_delete_request($request,$connection);
			default:		
				return http_response_code(405); //return a json with http code			
		}
	}
	
	public function execute_get_request($request, $connection) {		
		switch(self::number_of_elements($request)) {
	   	  case 1: 
				return self::select_all_actions($connection);

	   	  case 2:		
				return self::select_action_by_id($request[1], $connection);
			
	   	  default:
				return http_response_code(405); //return a json with http code			
		}
	
	}
	
	public function execute_post_request($request, $connection) {
	
	}
	
	public function execute_put_request($request,$parameters,$connection) {		
		 switch(self::number_of_elements($request)) {
	   	  case 1: 
				return self::get_control_layer_json($request[1],$parameters,$connection);  
			
	   	  default:
				return http_response_code(405); //return a json with http code			
		}
	}
	
	public function execute_delete_request($request, $connection) {
	
	}
	
	private function select_all_actions($connection) {
		return $this->db_action_controller->select_all_actions($connection);
	}
	
	private function select_action_by_id($id, $connection) {
		return $this->db_action_controller->select_action_by_id($id, $connection);
	}

	private function get_control_layer_json($id,$parameters,$connection) {
		return $this->db_action_controller->get_control_layer_json($id, $parameters,$connection);
	}
	
	private function number_of_elements($request) {
		return (sizeof($request) - 1);
	}
}


?>