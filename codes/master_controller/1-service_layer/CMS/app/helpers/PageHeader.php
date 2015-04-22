<?php

final
class PageHeader
{
    private $scripts = '';
    private $styles = '';
    private $header = '';

    public
    function __construct()
    {
        // Check if want to Hide Menu
        $hide_menu = isset($_GET['h']) ? $_GET['h'] : false;

        // Show the Page Title
        if (Framework::$app["layout"] != "search")
            $this->header .= Framework::$app["title"];

        // If Want to Hide Menu
        if ((boolean)$hide_menu)
            $this->header .= '<style>#Menu { display: none; } #Closing { display: none; } form { height: 400px;overflow-y: scroll; }</style>';

        // Create Styles and Scripts Instances
        $this->scripts = new Script();
        $this->styles = new Stylesheet();

        // Load jQuery
        if (Framework::$app["layout"] != "search")
            $this->scripts->add("/public/js/vendor/jquery.js");

        //Arbor Javascript
        if (Framework::$app["controller"] == "home" && Framework::$app["action"] == "index"):
            $this->scripts->add("/public/js/arbor/arbor.js");
            $this->scripts->add("/public/js/arbor/arbor-tween.js");
            $this->scripts->add("/public/js/arbor/arbor-graphics.js");
            $this->scripts->add("/public/js/cms/index.js");
        endif;

        // DataTable Scripts
        if (Framework::$app["controller"] != "home" && Framework::$app["action"] == "list"):
            $this->scripts->add("/public/js/cms/table.js");
        endif;

        //Menu Scripts
        if (Framework::$app["layout"] != "search" && ((Framework::$app["controller"] != "apps") && ((Framework::$app["action"] != "app_builder") || (Framework::$app["action"] != "open"))))
            $this->scripts->add("/public/js/cms/menu.js");

        //Form Scripts
        if (Framework::$app["layout"] != "search" && ((Framework::$app["controller"] != "apps") && ((Framework::$app["action"] != "app_builder") || (Framework::$app["action"] != "open"))))
            $this->scripts->add("/public/js/cms/form.js");

        //CSS Styles
        if (!((Framework::$app["action"] == "app_builder") && (Framework::$app["controller"] == "apps")) || !((Framework::$app["action"] == "open") && (Framework::$app["controller"] == "apps"))) :
            $this->styles->add("/public/css/styles.css");
            $this->styles->add("/public/css/foundation.css");
        endif;

        $this->styles->add("/public/css/font-awesome.css");

        //Show the Styles and Javascript
        $this->header .= $this->scripts->get_html();
        $this->header .= $this->styles->get_html();
        if (Framework::$app["layout"] != "search")
            $this->header .= "<link href='" . link_to('/public/images/favicon.ico') . "' rel='shortcut icon' type='image/x-icon' />";
    }

    public function clear_scripts(){
        $this->scripts = new Script();
    }

    public function clear_styles(){
        $this->styles = new Stylesheet();
    }

    /**
     * @param string $script_uri example: $header->add_script("/public/js/cms/menu.js");
     * @return bool result. Returns 'true' case everything went well and 'false' case file not found.
     */
    public function add_script($script_uri){

        //case it gets content outside of CMS or in public folder continue, otherwise, returns error.
        if(strpos($script_uri,"http")===0 || strpos($script_uri,"/public/")===0){

            //Checks if files exist in CMS' root path and adds it case it does.
            if(is_file(ROOT_PATH.$script_uri)) {
                $this->scripts->add($script_uri);
                return true;
            }

            //Checks if files exist in system root and adds it case it does.
            else if(is_file($script_uri)) {
                $script_uri = str_replace(ROOT_PATH,"",$script_uri);
                $this->scripts->add($script_uri);
                return true;
            }

            //Checks if it is an out file and adds it case it does.
            else if(strpos($script_uri,"http")===0) {
                $this->scripts->add($script_uri);
            }

            //returns error case file does not exist
            else{
                return false;
            }
        }

        //returns error case it is not getting content outside of CMS or in public folder.
        return false;
    }

    /**
     * @param string $style_uri example: $header->add_style("/public/css/styles.css");
     * @return bool result. Returns 'true' case everything went well and 'false' case file not found.
     */
    public function add_style($style_uri){

        //case it gets content outside of CMS or in public folder continue, otherwise, returns error.
        if(strpos($style_uri,"http")===0 || strpos($style_uri,"/public/")===0){

            //Checks if files exist in CMS' root path and adds it case it does.
            if(is_file(ROOT_PATH.$style_uri)) {
                $this->styles->add($style_uri);
                return true;
            }

            //Checks if files exist in system root and adds it case it does.
            else if(is_file($style_uri)) {
                $style_uri = str_replace(ROOT_PATH,"",$style_uri);
                $this->styles->add($style_uri);
                return true;
            }

            //Checks if it is an out file and adds it case it does.
            else if(strpos($style_uri,"http")===0) {
                $this->styles->add($style_uri);
            }

            //returns error case file does not exist
            else{
                return false;
            }
        }
        //returns error case it is not getting content outside of CMS or in public folder.
        return false;
    }

    public
    function return_header()
    {
        return $this->header;
    }

    public
    function print_html()
    {
        $header = $this->scripts->get_html();
        $header .= $this->styles->get_html();
        echo $header;
    }
}
