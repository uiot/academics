<?php

class SlaveController{
	
	var name;
	var type;
	var address;
	var description;


function get_name() 
{
	return $this->name;
} 	

function get_type() 
{
	return $this->type;
} 

function get_address() 
{
	return $this->address;
} 

function get_description() 
{
	return $this->description;
} 

function set_name($name) 
{
	$this->name = $name;
}

function set_type($type) 
{
	$this->type = $type;
}

function set_address($address) 
{
	$this->address = $address;
}	

function set_description($description) 
{
	$this->description = $description;
}

}

?>
