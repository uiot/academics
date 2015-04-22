<?php

class Request {
	
	var $uri;
	var $parameters;
	var $type;
	var $protocol;
	var $script_name; 
	
	public function __construct($uri, $type, $protocol, $script_name) {
			self::set_type($type); 			
			self::set_script_name($script_name);
			self::set_protocol($protocol);			
			self::set_uri(self::prepare_uri($uri));
			self::set_parameters($uri);			
	} 
	
	public function set_uri($uri) {
		$this->uri = $uri;
	}
	
	public function set_type($type) {
		$this->type = $type;
	}

	public function set_protocol($protocol) {
		$this->protocol = $protocol;	
	}
	
	public function set_script_name($script_name) {
		$this->script_name = $script_name;	
	}
	
	public function get_uri() {
		return $this->uri;
	}

	public function get_parameters() {
		return $this->parameters;
	}
	
	public function get_type() {
		return $this->type;
	}

	public function get_protocol() {
		return $this->protocol;
	}
	
	public function get_script_name() {
		return $this->script_name;
	}
	
	private function prepare_uri($raw_uri){
		 $tmp_uri = self::remove_script_parameters($raw_uri);
		 return self::remove_uri_parameters($tmp_uri);
	}

	private function remove_script_parameters($uri) {
		for($i= 0;$i < sizeof($this->script_name);$i++) {
      		if ($uri[$i] == $this->script_name[$i]){
				unset($uri[$i]);
	    	}
		}
		$new_uri = array_values($uri);
		return $new_uri;
	}

	private function remove_uri_parameters($uri){
		$last_uri_element = reset(explode('?', end($uri)));
		end($uri);
		$uri[key($uri)] = $last_uri_element;
		return $uri;
	}

	private function set_parameters($uri) {
		 $string_parameters = self::get_uri_parameters(end($uri));
		 $this->parameters = explode('&', $string_parameters);
	}

	private function get_uri_parameters($last_element) {
		if(!strpos($last_element,"?")) {
			return "";
		}
		return end(explode('?', $last_element));
	}

}

?>