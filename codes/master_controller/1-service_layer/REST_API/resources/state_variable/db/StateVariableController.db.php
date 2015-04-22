<?php

final class DBStateVarController {
	
	 public function select_all_state_vars($connection) {
		$encode = array();	
		$select_all_state_vars = "SELECT PK_Id, TE_Name, EN_Send_Events, EN_Multicast, EN_Data_Type FROM state_variable";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_all_state_vars);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
			$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);		
	}
	
	public function select_state_var_by_id($id, $connection) {	
		$encode = array();	
		$select_state_var_by_id = "SELECT PK_Id, TE_Name, EN_Send_Events, EN_Multicast, EN_Data_Type FROM state_variable
		 WHERE PK_Id = '".$id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_state_var_by_id);
				
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