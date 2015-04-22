<?php
header('P3P: CP="CAO PSA OUR"');
session_start();

/**
 *  Framework Class
 *  Initializing Structure and Main Class
 */

/**
 * Class Framework
 */
final
class Framework
{
    /**
     * @var array
     */
    public static $app = [];

    /**
     *
     * @var PageHeader holds page header accessible in all code.
     */
    public static $header;
    /**
     *
     * @var PageFooter holds page footer accessible in all code.
     */
    public static $footer;

    /**
     *
     */
    public static
    function render_content()
    {
        (include_once(ROOT_PATH . "/app/controllers/" . self::$app["controller"] . "/" . self::$app["action"] . ".php")) or Framework::uxdie('Failed to Load a Library', false);
    }

    /**
     * @param string $message_content
     * @param bool $need_die
     */
    public static
    function uxdie($message_content = "", $need_die = true)
    {
        if (empty(self::$app["layout"])):
            self::set_app("layout", "error");
            $error = [];
            $error["message"] = $message_content;
            self::set_app("error", $error);
            self::render_layout();
        endif;
        if ($need_die)
            die();
    }

    /**
     *
     */
    public static
    function render_layout()
    {
        (include_once(ROOT_PATH . "/layout/" . self::$app["layout"] . ".php")) or Framework::uxdie('Failed to Load a Library', false);
        DatabaseAdapter::destroy_connection();
        die();
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    static
    function get_app($key)
    {
        return Framework::$app[$key];
    }

    /**
     * @param $Key
     * @param $Value
     */
    public static
    function set_app($Key = '', $Value = '')
    {
        self::$app[$Key] = $Value;
    }

    /**
     *
     */
    public
    function start()
    {
        defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));

        // Load the Configuration
        $this->load_configuration();

        // Include the Helpers
        $this->include_helpers();

        // Start Database
        $this->start_database();

        // Include Models
        $this->include_models();

        // Include Mappers
        $this->include_mappers();

        // Start Display Engine
        $this->route_params();
        $this->get_layers();

        $this->set_layout();
        self::render_layout();
    }

    /**
     *
     */
    private
    function load_configuration()
    {
        @self::set_app("CONFIG", parse_ini_file(ROOT_PATH . "/system/config/config.ini"));
    }


    public
    function include_helpers()
    {
        foreach (glob(ROOT_PATH . "/app/helpers/*.php") as $filename)
            /** @noinspection PhpIncludeInspection */
            include_once($filename);
    }

    /**
     *
     */
    private
    function start_database()
    {
        DatabaseAdapter::create_connection(['user' => Framework::$app["CONFIG"]["DB_USERNAME"], 'pass' => Framework::$app["CONFIG"]["DB_PASSWORD"], 'name' => Framework::$app["CONFIG"]["DB_NAME"], 'host' => Framework::$app["CONFIG"]["DB_HOST_IP"], 'type' => Framework::$app["CONFIG"]["DB_TYPE"], 'port' => Framework::$app["CONFIG"]["DB_PORT"]]);
    }

    public
    function include_models()
    {
        foreach (glob(ROOT_PATH . "/app/models/*.php") as $filename)
            /** @noinspection PhpIncludeInspection */
            include_once($filename);
    }

    public
    function include_mappers()
    {
        foreach (glob(ROOT_PATH . "/app/mappers/*.php") as $filename)
            /** @noinspection PhpIncludeInspection */
            include_once($filename);
    }

    /**
     *
     */
    private
    function route_params()
    {
        //Gets the URL
        $request_url = explode('/', $_SERVER['REQUEST_URI']);
        $script_name = explode('/', $_SERVER['SCRIPT_NAME']);

        //Remove extra stuff from url before the index.php
        try {
            for ($i = 0; $i < sizeof($script_name); $i++)
                if (@$request_url[$i] == @$script_name[$i]):
                    unset($request_url[$i]);
                endif;
        } catch (Exception $e) {
            // Nothing to Do
        }

        //Parse clean parameters value to $command
        $command = array_values($request_url);

        //Try: fist the entity name, second the action name. The rest goes as parameter/value/parameter/value...
        try {
            @self::set_app("controller", ($command[0]) ? strtolower($command[0]) : "home");
            @self::set_app("action", ($command[1]) ? strtolower($command[1]) : "index");

            if (strpos($command[sizeof($command) - 1], "?") !== false):
                $c = explode("?", $command[sizeof($command) - 1]);
                $command[sizeof($command) - 1] = $c[0];
                unset($c[0]);
                $command[] = implode("&", $c);
            endif;

            unset($command[0]);
            unset($command[1]);

            self::set_app("command", $command);

            foreach ($command as $key => $value)
                if ($key % 2 == 0):
                    $_GET[$value] = "";
                    $last = $value;
                else:
                    $_GET[$last] = urldecode($value);
                endif;

            self::set_app("params", $_REQUEST);
        } catch (Exception $e) {
            // Nothing to Do
        }

    }

    private
    function get_layers()
    {
        $layer_configuration = ConfigMapper::get_all();
        self::$app["LAYER"]["cml_ip"] = $layer_configuration->get_cml_ip();
        self::$app["LAYER"]["cml_port"] = $layer_configuration->get_cml_port();
        self::$app["LAYER"]["svl_ip"] = $layer_configuration->get_svl_ip();
        self::$app["LAYER"]["svl_port"] = $layer_configuration->get_svl_port();
        self::$app["LAYER"]["cll_ip"] = $layer_configuration->get_cll_ip();
        self::$app["LAYER"]["cll_port_in"] = $layer_configuration->get_cll_port_in();
        self::$app["LAYER"]["cll_port_out"] = $layer_configuration->get_cll_port_out();
        self::$app["LAYER"]["max_cpu"] = $layer_configuration->get_max_cpu();
        self::$app["LAYER"]["max_mem"] = $layer_configuration->get_max_mem();
    }

    /**
     *
     */
    private
    function set_layout()
    {
        //Starts generic page headers and footers
        Framework::$header = new PageHeader();
        Framework::$footer = new PageFooter();
        //Sets the Page Title
        self::set_app("title", "<title >UIoT - Page " . ucwords(self::$app["action"] . " of " . self::$app["controller"]) . "</title >" . "\n");

        //Set the Layout of the Page
        if (!isset(self::$app["layout"]) || self::$app["layout"] != "error"):

            if (!isset($_SESSION['u_name']) && (self::$app["controller"] != "login")):
                self::set_app("controller", "login");
                self::set_app("action", "login");
                self::set_app("layout", "login");
            endif;

            if (self::$app["controller"] == "login")
                self::set_app("layout", "login");

            if ((self::$app["controller"] == "apps" && self::$app["action"] == "save"))
                self::set_app("layout", "blank");
            if (self::$app["controller"] == "debugger")
                self::set_app("layout", "debugger");
            if (self::$app["controller"] == "store")
                self::set_app("layout", "store");
            if (self::$app["controller"] == "search")
                self::set_app("layout", "search");
            if ((self::$app["action"] == "builder" || self::$app["action"] == "open") && self::$app["controller"] == "apps")
                self::set_app("layout", "app_builder");
            if (!isset(self::$app["layout"])):
                if ((self::$app["controller"] == "home") && (self::$app["action"] == "index")):
                    $tutorial = new Tutorial();
                    self::set_app('tutorial', $tutorial->render_content());
                endif;
                self::set_app("layout", "main");
            endif;
        endif;
    }
}
