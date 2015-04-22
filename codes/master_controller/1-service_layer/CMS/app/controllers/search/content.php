<?php
if (isset($_POST['search'])):

    $q = escape_text($_POST['search']);
    $slave = SlaveControllerMapper::get_by_name($q);
    $device = DeviceMapper::get_by_name($q);
    $service = ServiceMapper::get_by_name($q);
    $action = ActionMapper::get_by_name($q);
    $argument = ArgumentMapper::get_by_name($q);
    $variable = StateVariableMapper::get_by_name($q);
    $s_c = false;
    $s_d = false;
    $s_s = false;
    $s_a = false;
    $s_r = false;
    $s_v = false;

    echo '<li class="heading"><i class="fa fa-sitemap"> </i> System</li>';
    echo '<ul style="list-style-type: none;list-style-position: outside;">';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> Slave Controllers</li>';
    /* @var $slave SlaveControllerModel[] */
    foreach ($slave as $key => $row):
        $s_c = true;
        $link = link_to('/slavecontroller/edit/id/' . $row->get_unic_name());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_unic_name() . '</a></li>';
    endforeach;
    if (!$s_c)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> Devices</li>';
    /* @var $device DeviceModel[] */
    foreach ($device as $key => $row):
        $s_d = true;
        $link = link_to('/device/edit/id/' . $row->get_pk_id());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_friendly_name() . '</a></li>';
    endforeach;
    if (!$s_d)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> Services</li>';
    /* @var $service ServiceModel[] */
    foreach ($service as $key => $row):
        $s_s = true;
        $link = link_to('/service/edit/id/' . $row->get_pk_id());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_friendly_name() . '</a></li>';
    endforeach;
    if (!$s_s)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> Actions</li>';
    /* @var $action ActionModel[] */
    foreach ($action as $key => $row):
        $s_a = true;
        $link = link_to('/action/edit/id/' . $row->get_pk_id());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_name() . '</a></li>';
    endforeach;
    if (!$s_a)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> Arguments</li>';
    /* @var $argument ArgumentModel[] */
    foreach ($argument as $key => $row):
        $s_r = true;
        $link = link_to('/argument/edit/id/' . $row->get_pk_id());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_name() . '</a></li>';
    endforeach;
    if (!$s_r)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '<li class="heading"><i class="fa fa-sitemap"></i> State Variables</li>';
    /* @var $variable StateVariableModel[] */
    foreach ($variable as $key => $row):
        $s_v = true;
        $link = link_to('/argument/edit/id/' . $row->get_pk_id());
        echo '<li><a href="' . $link . '"><i class="fa fa-server"></i> ' . $row->get_name() . '</a></li>';
    endforeach;
    if (!$s_v)
        echo '<li><a href="javascript:void(0);">No Results</a></li>';

    echo '</ul>';
endif;