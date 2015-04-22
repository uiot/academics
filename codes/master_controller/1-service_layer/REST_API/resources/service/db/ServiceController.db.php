<?php

final class DBServiceController {

	public function select_all_services($connection) {
		$encode = array();	
		$select_all_services = "SELECT PK_Id, FK_Device, TE_Friendly_Name, TE_Service_Id, TE_Service_Type, TE_Description FROM service"; //WHERE BO_Deleted = 0		
		$result =  $connection->query($select_all_services);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
			$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);		
	}
	
	public function select_service_by_id($id, $connection) {	
		$encode = array();	
		$select_service_by_id = "SELECT PK_Id, FK_Device, TE_Friendly_Name, TE_Service_Id, TE_Service_Type, TE_Description FROM service
		WHERE PK_Id = '".$id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_service_by_id);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);
	}
	
	public function select_associated_actions($service_id, $connection) {
		$encode = array();	
		$select_associated_actions = "SELECT PK_Id, TE_Name FROM action WHERE FK_Service = '".$service_id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_associated_actions);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);
	}
	
	public function select_service_state_variables($service_id, $connection) {
		$encode = array();	
		$select_service_state_variables = "SELECT PK_Id, TE_Name, EN_Send_Events, EN_Multicast, EN_Data_Type
		FROM state_variable WHERE FK_Service = '".$service_id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_service_state_variables);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);
	}
	
}

?>