<?php
	
final class DBActionController {

	 public function select_all_actions($connection) {
		$encode = array();	
		$select_all_actions = "SELECT PK_Id,TE_Name,FK_Service FROM action WHERE BO_Deleted = 0;"; 
		$result =  $connection->query($select_all_actions);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
			$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);		
	}
	
	public function select_action_by_id($id, $connection) {	
		$encode = array();	
		$select_action_by_id = "SELECT PK_Id,TE_Name,FK_Service FROM action WHERE PK_Id =
		'{$id}' AND BO_Deleted = 0;";
		$result =  $connection->query($select_action_by_id);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);
	}

	private function select_action_name($id, $connection) {	
		$encode = array();	
		$select_action_by_id = "SELECT TE_Name FROM action WHERE PK_Id =
		'{$id}' AND BO_Deleted = 0;";
		$result =  $connection->query($select_action_by_id);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode = $row;
			}		
		}

		if(empty($encode)){
			return ""; //TODO: create excpetion
		}

		$connection = null; //destroying PDO object
		return $encode['TE_Name'];
	}

	public function get_control_layer_json($id,$parameters,$connection) {
		$service_id = self::get_action_service_id($id,$connection);
		$device_id = self::get_action_device_id($id,$connection);
		$action_name = self::select_action_name($id, $connection);
		return self::build_json($service_id,$device_id,$action_name,$parameters);
	} 

	private function get_action_service_id($id, $connection) {
		$encode = array();		
		$select_action_service = "SELECT FK_Service FROM action WHERE PK_Id = '{$id}' AND BO_Deleted = 0;";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_action_service);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode = $row;
			}		
		}
		$connection = null; //destroying PDO object

		if(empty($encode)){
			return ""; //TODO: create excpetion
		}

		return $encode['FK_Service'];
	}

	private function get_action_device_id($id, $connection) {
		$encode = array();	
		$select_action_device = "SELECT FK_device FROM service WHERE 
		PK_Id = (SELECT FK_Service FROM action WHERE PK_Id = '{$id}' AND BO_Deleted = 0);";
		$result =  $connection->query($select_action_device);

		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode = $row;
			}		
		}
		$connection = null; //destroying PDO object

		if(empty($encode)){
			return ""; //TODO: create excpetion
		}

		return $encode['FK_device'];
	}

	private function build_json($service_id,$device_id,$action_name,$parameters) {
		$data            = new stdClass();
		$data->device    = $device_id;
		$data->service   = $service_id;
		$data->action    = $action_name;
		$data->arguments = self::build_parameters_object($parameters);
		$data            = json_encode($data);
		return $data;
	}

	private function build_parameters_object($parameters) {
		$parameters_object            = new stdClass();
		foreach ($parameters as $key => $param) {
				$parameters_array = explode('=', $param);
				$parameters_object->{$parameters_array[0]} = $parameters_array[1]; 
		}
		return $parameters_object;
	}


}

?>