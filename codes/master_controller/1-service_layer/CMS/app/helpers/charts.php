<?php

/**
 * Class Charts
 */
final
class Charts
{
    /**
     * @var string
     */
    private $html_content = "";
    /**
     * @var stdClass
     */
    private $json_content;

    /**
     * Starts the Rendering of the Charts Tree
     */
    public
    function __construct()
    {
        $this->json_content = new stdClass();
        $this->json_content->nodes = new stdClass();
        $this->json_content->edges = new stdClass();
        $this->json_content->nodes->master = new stdClass();
        $this->json_content->nodes->master->color = "#f04124";
        $this->json_content->nodes->master->shape = "dot";
        $this->json_content->nodes->master->alpha = 1;
        $this->json_content->edges->master = new stdClass();
        $this->html_content .= '<div class="panel" id="Section4"><h5><i class="fa fa-sitemap"></i> Network</h5><ul class="accordion" data-accordion>';
        return $this->slave_controllers();
    }

    /**
     * Get the Slave slave_controllers Contents
     *
     * @return string
     */
    private
    function slave_controllers()
    {
        $slave_controllers_query = DatabaseAdapter::query("SELECT * FROM slave_controller  WHERE BO_Deleted='0'");
        $count_of_slave_controllers = DatabaseAdapter::num_rows($slave_controllers_query);
        if ($count_of_slave_controllers > 0):
            while ($slave_controllers_row = DatabaseAdapter::fetch_row($slave_controllers_query)):
                $this->json_content->nodes->{$slave_controllers_row['PK_Unic_Name']} = new stdClass();
                $this->json_content->nodes->{$slave_controllers_row['PK_Unic_Name']}->color = "#e7e7e7";
                $this->json_content->nodes->{$slave_controllers_row['PK_Unic_Name']}->shape = "dot";
                $this->json_content->nodes->{$slave_controllers_row['PK_Unic_Name']}->alpha = 1;
                $this->json_content->nodes->{$slave_controllers_row['PK_Unic_Name']}->link = link_to("/slavecontroller/edit/id/{$slave_controllers_row['PK_Unic_Name']}?h=true");
                $this->json_content->edges->master->{$slave_controllers_row['PK_Unic_Name']} = new stdClass();
                $this->html_content .= "<li class=\"accordion-navigation\"><a href=\"#panel{$slave_controllers_row['PK_Unic_Name']}\">Slave Controller <button class=\"tree_button logo_color\" link=\"" . link_to('/slavecontroller/edit/id/' . $slave_controllers_row['PK_Unic_Name']) . "\">#{$slave_controllers_row['PK_Unic_Name']}</button></a><div id=\"panel{$slave_controllers_row['PK_Unic_Name']}\" class=\"content panel\">";
                $this->devices($slave_controllers_row['PK_Unic_Name']);
                $this->html_content .= '</div></li>';
            endwhile;
            return 'go';
        else:
            $this->html_content .= '<div data-alert class="alert-box alert">To view Charts and trees, first insert a Slave controller thru menu.<a href="#" class="close">&times;</a></div>';
            return 'stop';
        endif;

    }

    /**
     * Get the Devices Content
     *
     * @param string $slave_controller_name
     */
    private
    function devices($slave_controller_name = "")
    {
        $devices_query = DatabaseAdapter::query("SELECT * FROM device WHERE FK_Slave_Controller='$slave_controller_name' AND BO_Deleted='0'");
        while ($devices_row = DatabaseAdapter::fetch_row($devices_query)):
            $this->json_content->nodes->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]} = new stdClass();
            $this->json_content->nodes->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]}->color = "#43AC6A";
            $this->json_content->nodes->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]}->alpha = 1;
            $this->json_content->nodes->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]}->alpha = 1;
            $this->json_content->nodes->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]}->link = link_to("/device/edit/id/" . $devices_row['PK_Id'] . '?h=true');
            @$this->json_content->edges->$slave_controller_name->{"Device {$devices_row['PK_Id']} - " . $devices_row["TE_Friendly_Name"]} = new stdClass();
            $this->html_content .= '<ul class="accordion" style="margin: 0;" data-accordion><li class="accordion-navigation">' . '<a href="#dev' . $devices_row['PK_Id'] . '">Device <button class="tree_button logo_color" link="' . link_to("/device/edit/id/" . $devices_row['PK_Id']) . '">#' . $devices_row['PK_Id'] . '-' . $devices_row['TE_Friendly_Name'] . '</button></a>' . '<div id="dev' . $devices_row['PK_Id'] . '" class="content panel">' . '<div class="row">' . '<div class="small-12 columns">' . '<a target="_blank" style="margin-bottom: 0;padding-bottom: 13px;right: 6px;top: -65px;position: absolute;" id="dev" class="button secondary small logo_color" href="' . link_to("/etc/xml/devices/" . $devices_row['PK_Id'] . ".xml") . '"><i class="fa fa-file-code-o"></i> XML File</a>';
            $this->services($devices_row['PK_Id'], $devices_row["TE_Friendly_Name"]);
            $this->html_content .= '</div></div></div></li></ul>';
        endwhile;
    }

    /**
     * Get the Services Content
     *
     * @param string $device_id
     * @param string $device_name
     */
    private
    function services($device_id = "", $device_name = "")
    {
        $services_query = DatabaseAdapter::query("SELECT * FROM service WHERE FK_Device='$device_id' AND BO_Deleted='0'");
        while ($services_row = DatabaseAdapter::fetch_row($services_query)):
            $this->json_content->nodes->{"Service {$services_row['PK_Id']} - " . $services_row["TE_Friendly_Name"]} = new stdClass();
            $this->json_content->nodes->{"Service {$services_row['PK_Id']} - " . $services_row["TE_Friendly_Name"]}->color = "#007095";
            $this->json_content->nodes->{"Service {$services_row['PK_Id']} - " . $services_row["TE_Friendly_Name"]}->alpha = 1;
            $this->json_content->nodes->{"Service {$services_row['PK_Id']} - " . $services_row["TE_Friendly_Name"]}->link = link_to("/service/edit/id/" . $services_row['PK_Id'] . '?h=true');
            @$this->json_content->edges->{"Device {$device_id} - " . $device_name}->{"Service {$services_row['PK_Id']} - " . $services_row["TE_Friendly_Name"]} = new stdClass();
            $this->html_content .= '<ul class="accordion" style="margin: 0;" data-accordion><li class="accordion-navigation">' . '<a href="#serv' . $services_row['TE_Friendly_Name'] . '">Service <button class="tree_button logo_color" link="' . link_to("/service/edit/id/" . $services_row['PK_Id']) . '">#' . $services_row['PK_Id'] . '-' . $services_row['TE_Friendly_Name'] . '</button></a>' . '<div id="serv' . $services_row['TE_Friendly_Name'] . '" class="content panel">' . '<div class="row">' . '<div class="small-12 columns">' . '<a target="_blank" style="margin-bottom: 0;padding-bottom: 13px;right: 6px;top: -65px;position: absolute;" id="dev" class="button secondary small logo_color" href="' . link_to("/etc/xml/services/" . $services_row['PK_Id'] . ".xml") . '"><i class="fa fa-file-code-o"></i> XML File</a>';
            $this->actions($services_row['PK_Id'], $services_row["TE_Friendly_Name"]);
            $this->html_content .= '</div></div></div></li></ul>';
        endwhile;
    }

    /**
     * Get the Actions Content
     *
     * @param string $service_id
     * @param string $service_name
     */
    private
    function actions($service_id = "", $service_name = "")
    {
        $actions_query = DatabaseAdapter::query("SELECT * FROM action WHERE FK_Service='$service_id' AND BO_Deleted='0'");
        while ($actions_row = DatabaseAdapter::fetch_row($actions_query)):
            $this->json_content->nodes->{"Action {$actions_row['PK_Id']} - " . $actions_row['TE_Name']} = new stdClass();
            $this->json_content->nodes->{"Action {$actions_row['PK_Id']} - " . $actions_row['TE_Name']}->color = "#f08a24";
            $this->json_content->nodes->{"Action {$actions_row['PK_Id']} - " . $actions_row['TE_Name']}->alpha = 1;
            $this->json_content->nodes->{"Action {$actions_row['PK_Id']} - " . $actions_row['TE_Name']}->link = link_to("/action/edit/id/" . $actions_row['PK_Id'] . '?h=true');
            @$this->json_content->edges->{"Service $service_id - " . $service_name}->{"Action {$actions_row['PK_Id']} - " . $actions_row['TE_Name']} = new stdClass();
            $this->html_content .= '<ul class="accordion" style="margin: 0;" data-accordion>' . '<li class="accordion-navigation">' . '<a href="#act' . $actions_row['TE_Name'] . '">Action <button class="tree_button logo_color" link="' . link_to("/action/edit/id/" . $actions_row['PK_Id']) . '">#' . $actions_row['PK_Id'] . '-' . $actions_row['TE_Name'] . '</button></a>';
            $this->html_content .= '<div id="act' . $actions_row['TE_Name'] . '" class="content panel">';
            $this->variables($service_id);
            $this->html_content .= '</div></li></ul>';
        endwhile;
    }

    /**
     * @param string $service_id
     */
    private
    function variables($service_id = "")
    {
        $variables_query = DatabaseAdapter::query("SELECT * FROM state_variable WHERE FK_Service='$service_id' AND BO_Deleted='0'");
        while ($variables_row = DatabaseAdapter::fetch_row($variables_query)):
            $this->html_content .= "<tr data-tt-id='{$variables_row['TE_Name']}'><td><b>State Variable" . ' <button class="tree_button logo_color" link="' . link_to("/statevariable/edit/id/" . $variables_row['PK_Id']) . '">' . "#</b>{$variables_row['PK_Id']}-{$variables_row['TE_Name']} </button></td></tr>";
        endwhile;
    }

    /**
     * @return string
     */
    public
    function render_html()
    {
        if (empty($this->html_content)):
            $this->html_content = "   ";
        else:
            $this->html_content .= '</ul></div>';
        endif;
        return $this->html_content;
    }

    /**
     * @return stdClass
     */
    public
    function render_json()
    {
        return $this->json_content;
    }
}