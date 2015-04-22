<?php	

final class DBSlaveController {
	
public function select_all_slaves($connection) {
	$encode = array();	
	$select_all_slaves = "SELECT PK_Unic_Name,TE_Type,TE_Address,TE_Description FROM slave_controller";	//WHERE BO_Deleted = 0	
	$result =  $connection->query($select_all_slaves);
				
	if($result->rowCount() > 0) {
		while($row = $result->fetch()) {			
			$encode[] = $row;
		}		
	}
	$connection = null; //destroying PDO object
	return json_encode($encode);		
}

public function select_slave_by_unic_name($unic_name, $connection) {	
	$encode = array();	
	$select_slaves_by_unic_name = "SELECT PK_Unic_Name,TE_Type,TE_Address,TE_Description FROM slave_controller WHERE PK_Unic_Name =
	'".$unic_name."';";	//WHERE BO_Deleted = 0	
	$result =  $connection->query($select_slaves_by_unic_name);
				
	if($result->rowCount() > 0) {
		while($row = $result->fetch()) {			
			$encode[] = $row;
		}		
	}
	$connection = null; //destroying PDO object
	return json_encode($encode);
}

public function select_all_associated_devices($unic_name,$connection) {
	$encode = array();	
	$select_associated_devices = "SELECT PK_Id, TE_UDN, FK_Slave_Controller, TE_friendly_Name, TE_Device_Type FROM device WHERE  		
	FK_Slave_Controller = '".$unic_name."';";	//WHERE BO_Deleted = 0	
	$result =  $connection->query($select_associated_devices);
				
	if($result->rowCount() > 0) {
		while($row = $result->fetch()) {			
			$encode[] = $row;
		}		
	}
	$connection = null; //destroying PDO object
	return json_encode($encode);
}


	public function insert_slave($connection, $slave_controller) {
		
		$name = $slave_controller->get_name();
		$type = $slave_controller->get_type();
		$address = $slave_controller->get_address();
		$description = $slave_controller->get_description();

		$stmt = $connection->prepare("INSERT INTO slave_controller (PK_Unic_Name,TE_Type,TE_Address,TE_Description) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss",$name,$type,$address,$description);
		$stmt->execute;

		$stmt->close();
		$connection->close(); 
	
	}	
	
}		

?>
