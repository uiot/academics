<?php

/**
 * Class Tutorial
 */
final
class Tutorial
{
    /**
     * @var string
     */
    private $message;

    /**
     * Starts the Tutorial Parsering
     */
    public
    function __construct()
    {
        $this->message = '<ol class="joyride-list" data-joyride>';
        switch ($this->check_tutorial()):
            case 'I_Passed':
                add_cookie('TheTutorial', 'I_Passed');
                break;
            case 'D_Passed':
                $this->message();
                add_cookie('TheTutorial', 'I_Passed');
                break;
            case 'D_Started':
                $this->message();
                add_cookie('TheTutorial', 'D_Passed');
                break;
            default:
                $this->message();
                add_cookie('TheTutorial', 'D_Started');
                break;
        endswitch;
    }

    /**
     * Checks the Tutorial Existent of Cookie
     *
     * @return string
     */
    private
    function check_tutorial()
    {
        if (!empty($_COOKIE['TheTutorial'])):
            if ($_COOKIE['TheTutorial'] == 'D_Passed')
                return 'D_Passed';
            else if ($_COOKIE['TheTutorial'] == 'I_Passed')
                return 'I_Passed';
            else
                return 'WTF';
        else:
            return 'D_Started';
        endif;
    }

    /**
     * Makes the Messages
     */
    private
    function message()
    {
        $this->add_message('', '', 'Next', '', 'prev_button:false;', "Hello!", "Hello And Welcome to UIoT Admin Panel. Now We Going to Give to You a Tutorial");
        $this->add_message('Section2', 'custom so-awesome', 'Next', 'Prev', 'tip_location:top;', "Stop #1", "That's it's the Menu, In This Place you Can See Everything from the CMS");
        $this->add_message('Section3', '', 'Next', 'Prev', 'tip_location:top;tip_animation:fade', "Stop #2", "Here you Can see a Graph Tree with the Componentes of the System!");
        $this->add_message('Section4', '', 'Next', 'Prev', 'tip_location:top;tip_animation:fade', "Stop #3", "Here you Can see all devices, Services, Actions, Arguments and State Varaibles from the System!");
        $this->add_message('', '', 'End', 'Prev', '', "Okay!", "Thanks for Read our Tutorial!");
    }

    /**
     * Add and Message
     *
     * @param string $div_id
     * @param string $div_class
     * @param string $next_text
     * @param string $prev_text
     * @param string $data_options
     * @param string $title
     * @param string $text
     */
    private
    function add_message($div_id = '', $div_class = '', $next_text = '', $prev_text = '', $data_options = '', $title = '', $text = '')
    {
        $this->message .= "<li data-prev-text=\"{$prev_text}\" data-class=\"{$div_class}\" data-id=\"{$div_id}\" data-text=\"{$next_text}\" data-options=\"{$data_options}\"><h4>{$title}</h4><p>{$text}</p></li>" . "\n";
    }

    /**
     * Render the Content
     *
     * @return string
     */
    public
    function render_content()
    {
        $this->message .= "</ol>";
        return $this->message;
    }
}