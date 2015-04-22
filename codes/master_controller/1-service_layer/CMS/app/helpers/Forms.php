<?php

/**
 * Class Forms
 *
 * Create and Manage HTML Forms
 *
 */
final
class Forms
{
    /**
     * @var string
     * HTML Content from the Buttons
     */
    private $buttons_content = '';
    /**
     * @var string
     * HTML Content from Entire Form
     */
    private $form_content = '';

    /**
     * @param string $main_header
     * @param string $action_to
     */
    public
    function __construct($main_header = '', $action_to = '')
    {
        $this->form_content .= '<form action="' . link_to($action_to) . '" method="POST" data-abide>';
        if (!empty($main_header))
            $this->form_content .= '<h2>' . $main_header . '</h2>';
        $this->form_content .= '<div class="panel radius">';
    }

    /**
     *
     * @param array $input_attributes
     * @param string $label_text
     * @param string $error_message
     */
    public
    function add_input($input_attributes = [], $label_text = 'default input', $error_message = 'undefined error', $required_id = '')
    {
        $input = "<div class='row collapse'><div class='large-4 columns'><span class='prefix'>$label_text</span></div><div class='large-8 columns'><label><input ";
        foreach ($input_attributes as $field => $value):
            $input .= " $field='$value' ";
        endforeach;
        $this->form_content .= $input . " data-equalto='$required_id' required></label><small class='error'>$error_message</small></div></div>";

    }

    /**
     * @param array $hidden_attributes
     */
    public
    function add_hidden($hidden_attributes = [])
    {
        $input = "<input type='hidden' ";
        foreach ($hidden_attributes as $field => $value):
            $input .= " $field='$value' ";
        endforeach;
        $this->form_content .= $input . ' />';

    }

    /**
     * @param array $select_attributes
     * @param array $select_data
     * @param string $label_text
     * @param string $error_message
     */
    public
    function add_select($select_attributes = [], $select_data = [], $label_text = 'default input', $error_message = 'undefined error')
    {
        $input = "<div class='row collapse'><div class='large-4 columns'><span class='prefix'>$label_text</span></div><div class='large-8 columns'><label><select ";
        foreach ($select_attributes as $field => $value):
            $input .= " $field='$value' ";
        endforeach;
        $input .= ' required>';
        foreach ($select_data as $select_value => $object):
            $input .= "<option value='{$object["value"]}'>{$object["label"]}</option>";
        endforeach;
        $this->form_content .= $input . "</select></label><small class='error'>$error_message</small></div></div>";
    }

    /**
     * @param array $text_area_attributes
     * @param string $text_area_value
     * @param string $label_text
     * @param string $error_message
     */
    public
    function add_text_area($text_area_attributes = [], $text_area_value = '', $label_text = 'default input', $error_message = 'undefined error')
    {
        $input = "<div class='row collapse'><div class='large-4 columns'><span class='prefix'>$label_text</span></div><div class='large-8 columns'><label><textarea ";
        foreach ($text_area_attributes as $field => $value):
            $input .= " $field='$value' ";
        endforeach;
        $this->form_content .= $input . " required>$text_area_value</textarea></label><small class='error'>$error_message</small></div></div>";
    }

    /**
     * Add an Header <h3>Header</h3> on the Form
     *
     * @param string $content_text Header Text
     */
    public
    function add_header($content_text = '')
    {
        $this->form_content .= '<h3>' . $content_text . '</h3>';
    }

    /**
     * Add a <p>Text</p> On the Form
     *
     * @param string $content_text P Text
     */
    public
    function add_text($content_text = '')
    {
        $this->form_content .= '<p>' . $content_text . '</p>';
    }

    /**
     * Add an Alert Div from Foundation on the Form
     *
     * @param string $content_text Alert Text
     */
    public
    function add_alert($content_text = '')
    {
        $this->form_content .= '<div data-alert class="alert-box">' . $content_text . '</div >';
    }

    /**
     * @param string $button_name
     * @param string $button_class
     * @param string $button_value
     * @param string $button_onclick_callback
     */
    public
    function add_button($button_name = '', $button_class = '', $button_value = '', $button_onclick_callback = '')
    {
        if (empty($this->buttons_content)):
            $this->buttons_content = '<dl class="sub-nav" style="background-color: #f2f2f2;padding: 10px 10px 10px 0;border: 1px solid #d8d8d8;border-radius:3px;"><dt>Actions:</dt>';
            $this->buttons_content .= '<dd class="active"><a class="' . $button_class . '" name="' . $button_name . '" onclick="' . $button_onclick_callback . '">' . $button_value . '</a></dd>';
        else:
            $this->buttons_content .= '<dd class="active"><a class="' . $button_class . '" name="' . $button_name . '" onclick="' . $button_onclick_callback . '">' . $button_value . '</a></dd>';
        endif;

    }

    /**
     * Render the Content of the Form
     */
    public
    function render_form()
    {
        $this->form_content .= '</div></dl>';
        $this->form_content .= $this->buttons_content;
        echo $this->form_content . '</form>';
    }
}