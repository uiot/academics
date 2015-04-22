<?php

final
class PageFooter
{

    private $scripts = '';
    private $styles = '';
    private $footer = '';
    private $scripts_codes = '';

    public
    function __construct()
    {
        //Footer Scripts and Styles
        $this->scripts = new Script();
        $this->styles = new Stylesheet();

        //Foundation Necessary Scripts
        if (!((Framework::$app["action"] == "app_builder") && (Framework::$app["controller"] == "apps")) || !((Framework::$app["action"] == "open") && (Framework::$app["controller"] == "apps"))):
            $this->scripts->add("/public/js/foundation/foundation.js");
            $this->scripts->add("/public/js/vendor/modernizr.js");
            $this->scripts->add("/public/js/foundation/foundation.offcanvas.js");
        endif;

        // Doenst Need That on the Login and Error Pages
        if ((Framework::$app["layout"] != "error") || (Framework::$app["layout"] != "login")):

            // Tutorial Only for Index And Socket.IO
            if ((Framework::$app["controller"] == "home") && (Framework::$app["action"] == "index")):
                $this->scripts->add("/public/js/foundation/foundation.joyride.js");
                $this->scripts->add("/public/js/io/socket.io.js");
            endif;

            // This is Not Neccessary in the Store
            if ((Framework::$app["controller"] != "store") && (Framework::$app["controller"] != "apps")):
                $this->scripts->add("/public/js/foundation/foundation.abide.js");
                $this->scripts->add("/public/js/foundation/foundation.accordion.js");
                $this->scripts->add("/public/js/foundation/foundation.reveal.js");
            endif;

            // Load the DataTables
            if ((Framework::$app["controller"] != "home") && (Framework::$app["action"] == "list")):
                $this->scripts->add("/public/js/vendor/datatables.js");
            endif;

            // Debugger Scripts
            if (Framework::$app["controller"] == "debugger"):
                $this->scripts->add("/public/js/charts/chart.js");
                $this->scripts->add("/public/js/io/socket.io.js");
                $this->scripts->add("/public/js/foundation/foundation.tab.js");
            endif;

            //Bottom Scripts
            $this->scripts_codes = new ScriptCode();

            // The Bottom Scripts Codes
            $this->scripts_codes->add('function smit () { $ ("form").submit (); }');
            $this->scripts_codes->add('$ (document).foundation ();');

            // Only for Tutorial
            if ((Framework::$app["controller"] == "home") && (Framework::$app["action"] == "index")):
                $this->scripts_codes->add('$ (document).foundation (\'joyride\', \'start\');');
            endif;

            if ((Framework::$app["controller"] == "apps") && ((Framework::$app["action"] == "app_builder") || (Framework::$app["action"] == "open"))):
                $this->styles->add("/public/css/styles.css");
                $this->styles->add("/public/css/foundation.css");
                $this->styles->add("/public/css/docs.css");
                $this->scripts->add("/public/js/vendor/ui.js");
            endif;

        endif;

        //Load Footer
        $this->footer .= $this->scripts->get_html();
        $this->footer .= $this->styles->get_html();
        $this->footer .= $this->scripts_codes->get();
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
    function return_footer()
    {
        return $this->footer;
    }

    public
    function print_html()
    {
        $header = $this->scripts->get_html();
        $header .= $this->styles->get_html();
        echo $header;
    }

}
