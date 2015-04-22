<?php

include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/control/Request.control.php";
include $_SERVER['DOCUMENT_ROOT']."/service_rest_api/communication/ExternalCommunicator.communication.php";


class RequestInput {


	var $request_control;
	var $external_communicator;

	public function __construct() {
		self::set_request_control(new RequestControl());
		self::set_external_comunnicator(new ExternalCommunicator(SCKT_PORT,SCKT_ADDRESS));
	}

	public function start() {
		return self::submit_request();
	}

	private function set_request_control($request_control) {
		$this->request_control = $request_control;
	}

	private function set_external_comunnicator($external_communicator) {
		$this->external_communicator = $external_communicator;
	}

	private function submit_request() {
		return $this->external_communicator->submit_request(self::create_request());
	}

	private function create_request() {
		$request_uri = self::get_request_uri_array();
		$request_method = $_SERVER['REQUEST_METHOD'];
		$server_protocol = $_SERVER['SERVER_PROTOCOL'];
		$script_name = self::get_script_name_array();

		return $this->request_control->create_request($request_uri, $request_method, $server_protocol, $script_name);
	}

	private function get_request_uri_array() {
		return explode('/', $_SERVER['REQUEST_URI']);
	}

	private function get_script_name_array() {
		return explode('/',$_SERVER['SCRIPT_NAME']);
	}


}
?>