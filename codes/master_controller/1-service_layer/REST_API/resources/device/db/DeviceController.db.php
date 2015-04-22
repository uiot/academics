<?php

final class DBDeviceController {

  public function select_all_devices($connection) {
		$encode = array();	
		$select_all_devices = "SELECT PK_Id,TE_UDN,FK_Slave_Controller,TE_Friendly_Name,TE_Device_Type FROM device"; //WHERE BO_Deleted = 0	
		$result =  $connection->query($select_all_devices);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
			$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);		
	}
	
	public function select_device_by_id($id, $connection) {	
		$encode = array();	
		$select_device_by_id = "SELECT PK_Id,TE_UDN,FK_Slave_Controller,TE_Friendly_Name,TE_Device_Type FROM device WHERE PK_Id =
		'".$id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_device_by_id);
				
		if($result->rowCount() > 0) {
			while($row = $result->fetch()) {			
				$encode[] = $row;
			}		
		}
		$connection = null; //destroying PDO object
		return json_encode($encode);
	}
	
	public function select_associated_services($device_id, $connection) {
		$encode = array();	
		$select_associated_services = "SELECT PK_Id, TE_Friendly_Name, TE_Service_Id, TE_Service_Type, TE_Description 
		FROM service WHERE FK_Device = '".$device_id."';";	//WHERE BO_Deleted = 0	
		$result =  $connection->query($select_associated_services);
				
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

