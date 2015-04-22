<?php

final
class RestController
{
    private $command;
    private $device;
    private $service;
    private $action;
    private $input_arguments;
    private $data;
    private $response_json;

    public
    function receive_api_parameters()
    {
        //receives arguments
        $this->command = Framework::$app["command"];

        //setup device
        $this->device = escape_text($this->command[3]);

        //setup service
        $service_id = escape_text($this->command[4]);
        $this->service = DatabaseAdapter::fetch_row(DatabaseAdapter::query("SELECT TE_Service_Id FROM Service WHERE FK_Device=$this->device AND PK_Id=$service_id AND BO_Deleted=0"))["TE_Service_Id"];

        //setup action
        $this->action = escape_text($this->command[5]);

        //setup arguments
        $this->receive_input_arguments($arguments_initial_array = explode("&", $this->command[6]));
    }

    public
    function receive_input_arguments($arguments_initial_array = [])
    {
        //sets arguments as objects
        $arguments = [];
        $input_args = new stdClass();
        foreach ($arguments_initial_array as $key => $value) {
            $arg = explode("=", $value);
            $name = $arg[0];
            $val = urldecode($arg[1]);
            $input_args->$name = $val;
        }
        $this->input_arguments = $input_args;
    }

    public
    function build_control_layer_json()
    {
        //setup json for master controller
        $this->data = new stdClass();
        $this->data->device = $this->device;
        $this->data->service = $this->service;
        $this->data->action = $this->action;
        $this->data->arguments = $this->input_arguments;
        $this->data = json_encode($this->data);
    }

    public
    function send_request_to_control_layer()
    {
        //sends request to master controller control layer
        $s = new SocketCommunication();
        $this->response_json = $s->send_data($this->data);
    }

    public
    function print_output_json_from_control_layer()
    {
        //prints output from request
        echo str_replace("\n", " ", $this->response_json);
    }

}
