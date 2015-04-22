<?php

/**
 * Controls Handling of Store Redirection
 * Class Store_Handler
 */
final
class StoreHandler
{
    /**
     * @var string
     */
    private $action = '';
    /**
     * @var string
     */
    private $error = '';
    /**
     * @var string
     */
    private $store_url = '';
    /**
     * @var array
     */
    private $message = [];

    /**
     * Makes the Store Message Handler
     */
    public
    function __construct()
    {
        $this->receive_parameters();
        $this->route_store();
    }

    /**
     * Receive the Params
     */
    private
    function receive_parameters()
    {
        $this->action = (isset($_GET["action"])) ? $_GET["action"] : "none";
        $this->error = (isset($_GET["error"])) ? $_GET["error"] : "none";
    }

    /**
     * Route the Store Url
     */
    private
    function route_store()
    {
        switch ($this->action):
            case "device_subscription":
                $this->device_subscription();
                break;
            default:
                $this->store_url = "http://store.uiot.org/store/cms/?r=" . urlencode(clink_to(''));
                $this->render_store_in_iframe();
                break;
        endswitch;
    }

    /**
     * If the Request is for the Import Device
     */
    private
    function device_subscription()
    {
        if (isset($_GET["device_id"]) && is_numeric($_GET["device_id"]))
            $device_id = $_GET["device_id"];
        else
            $this->error = "empty_device";
        switch ($this->error):
            case "local_error":
                $this->add_message("There was an error while sending data to uiot.org.");
                $this->store_url = "http://store.uiot.org/panel/list/";
                break;
            case "host_error":
                $this->add_message("There was an error while saving device on uiot.org.");
                $this->store_url = "http://store.uiot.org/panel/list/";
                break;
            case "empty_device":
                $this->add_message("There was an error while receiving device id from uiot.org.");
                $this->store_url = "http://store.uiot.org/panel/list/";
                break;
            default:
                $this->store_url = "http://store.uiot.org/panel/add_device/device_id/" . $device_id;
                break;
        endswitch;
        $this->render_store_in_mainwindow();
    }

    /**
     * Add a Message
     *
     * @param $message
     */
    private
    function add_message($message)
    {
        $this->message [] = $message;
    }

    /**
     * Render the Store in a MainWindow
     */
    private
    function render_store_in_mainwindow()
    {
        echo "Uploading device... <META http-equiv='refresh' content='0;URL={$this->store_url}'>";
    }

    /**
     * Render the Store in a IFrame
     */
    private
    function render_store_in_iframe()
    {
        echo "<iframe style='width: 100%;height: 100%;' src='{$this->store_url}'></iframe>";
    }

}