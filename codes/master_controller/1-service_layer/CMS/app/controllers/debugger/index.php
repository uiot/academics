<?php
if (isset($_POST['do'])):

    $model = new ConfigModel();
    $model->set_cml_ip($_POST['cml_ip']);
    $model->set_cml_port($_POST['cml_port']);
    $model->set_svl_ip($_POST['svl_ip']);
    $model->set_svl_port($_POST['svl_port']);
    $model->set_cll_ip($_POST['cll_ip']);
    $model->set_cll_port_in($_POST['cll_port_in']);
    $model->set_cll_port_out($_POST['cll_port_out']);
    $model->set_max_cpu($_POST['max_cpu']);
    $model->set_max_mem($_POST['max_mem']);
    ConfigMapper::update($model);

endif;
?>
<div class="nope"><br/></div>
<div class="row">
    <div class="large-4 columns">
        <ul class="tabs">
            <li class="tab-title">
                <a href="<?= link_to('/home/index/'); ?>"><i class="fa fa-reply "></i> Back</a>
            </li>
        </ul>
    </div>
    <div class="large-8 columns">
        <div class="row">
            <div class="large-4 columns">
                <br/>
            </div>
            <div class="large-8 columns">
                <ul class="tabs" data-tab>
                    <li class="tab-title active"><a href="#stats" data-toggle="tab"><i class="fa fa-laptop "></i>
                            Stats</a></li>
                    <li class="tab-title"><a href="#console" data-toggle="tab"><i class="fa fa-terminal "></i>
                            Control</a></li>
                    <li class="tab-title"><a href="#edit" data-toggle="tab"><i class="fa fa-pencil-square-o "></i>
                            Edit Sys</a></li>
                </ul>
            </div>
        </div>
        <br/>
    </div>
</div>
<br/>
<div class="row">
    <div class="large-12 columns">
        <div class="alert-box alert" id="socket_error" style="display:none;"><i
                class="fa fa-exclamation-triangle"></i> We
            were unable to connect to the main server, there may be
            network
            issues currently. This message will disappear
            when we are able to connect, you do not need to refresh.
        </div>
    </div>
</div>
<div class="tabs-content">
    <div class="content active" id="stats">
        <div class="row">
            <div class="large-6 columns">
                <h3 class="nopad">Memory Usage</h3>
                <hr/>
                <div class="row">
                    <canvas id="memoryChart" width="280" height="150" style="margin-left:20px;"></canvas>
                    <p style="text-align:center;margin-top:-15px;" class="text-muted">
                        <small>Time (10s Increments)</small>
                    </p>
                    <p class="graph-yaxis text-muted" style="margin-top: -36px !important;">
                        <small>Memory Usage (Mb)</small>
                    </p>
                </div>
            </div>
            <div class="large-6 columns">
                <h3 class="nopad">CPU Usage</h3>
                <hr/>
                <div class="row">
                    <canvas id="cpuChart" width="280" height="150" style="margin-left:20px;"></canvas>
                    <p style="text-align:center;margin-top:-15px;" class="text-muted">
                        <small>Time (10s Increments)</small>
                    </p>
                    <p class="graph-yaxis text-muted" style="margin-top: -36px !important;">
                        <small>CPU Usage (%)</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <h3>Server Information</h3>
                <hr/>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td><strong>Connection Information</strong></td>
                        <td><?= (Framework::$app["LAYER"]["cml_ip"] . ':' . Framework::$app["LAYER"]["cml_port"]); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Server Max Memory Usage</strong></td>
                        <td><?= Framework::$app["LAYER"]["max_mem"]; ?> MB</td>
                    </tr>
                    <tr>
                        <td><strong>Server Max Disk Usage</strong></td>
                        <td><?= Framework::$app["LAYER"]["max_cpu"]; ?> MB</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="content" id="console">
        <div class="row">
            <div class="large-12 columns">
                    <textarea id="live_console" class="form-control console"
                              readonly="readonly">Hello World!</textarea>
            </div>
        </div>
        <div class="row">
            <div class="large-6 columns">
                <hr/>
                <form action="#" method="post" id="console_command">
                    <div class="row collapse" style="display: initial;">
                        <div class="row collapse" style="display: initial;">
                            <div class="small-10 columns">
                                <input type="text" class="form-control"
                                       style="line-height: 2.0500rem;height: 2.0500rem;" name="command"
                                       id="ccmd"
                                       placeholder="Digit a Command.."/>
                            </div>
                            <div class="small-2 columns">
                                <a id="sending_command" style="line-height: 2.0500rem;height: 2.0500rem;"
                                   class="button postfix">&rarr;</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="alert alert-danger" id="sc_resp" style="display:none;margin-top: 15px;"></div>
            </div>
            <div class="large-6 columns" style="text-align:center;">
                <hr/>
                <div class="row collapse" style="display: initial;">
                    <button class="button tiny success" id="on">Start Server</button>
                    <button class="button tiny" id="restart">Restart Server</button>
                    <button class="button tiny alert" id="off">Stop Server</button>
                    <button class="button tiny secondary" data-reveal-id="pauseConsole" id="pause_console">Raw
                    </button>
                    <div style="margin-top:5px;">
                        <small><p class="text-muted">My server isn't responding! Please <code id="kill_proc"
                                                                                              style="cursor: pointer;">kill
                                    it</code>.
                            </p></small>
                    </div>
                    <div id="pw_resp" style="display:none;margin-top: 15px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="content" id="edit">
        <div class="row">
            <div class="large-8 columns">
                <div class="panel radius">
                    <h5>Edit Configuration</h5>

                    <form method="POST" action="<?= link_to('/debugger/index/'); ?>">
                        <input type="hidden" name="do" value="blank"/>
                        <div class="row">
                            <div class="large-12 columns">
                                <hr />
                                <h5> Service layer Configuration</h5>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-6 columns">
                                <label>IPv4 Address
                                    <input type="text" name="svl_ip"
                                           value="<?= Framework::$app["LAYER"]["svl_ip"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-6 columns">
                                <label>Eventing Messages Port
                                    <input type="text" name="svl_port"
                                           value="<?= Framework::$app["LAYER"]["svl_port"]; ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <hr />
                                <h5> Control layer Configuration</h5>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-4 columns">
                                <label>IPv4 Address
                                    <input type="text" name="cll_ip"
                                           value="<?= Framework::$app["LAYER"]["cll_ip"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-4 columns">
                                <label>Eventing Messages Port
                                    <input type="text" name="cllport"
                                           value="<?= Framework::$app["LAYER"]["cll_port_in"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-4 columns">
                                <label>Control Messages Port
                                    <input type="text" name="cllport"
                                           value="<?= Framework::$app["LAYER"]["cll_port_out"]; ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <hr />
                                <h5> Communication layer Configuration</h5>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-4 columns">
                                <label>IPv4 Address
                                    <input type="text" name="cml_ip"
                                           value="<?= Framework::$app["LAYER"]["cml_ip"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-4 columns">
                                <label>Control Messages Port
                                    <input type="text" name="cml_port"
                                           value="<?= Framework::$app["LAYER"]["cml_port"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-4 columns">
                                <label>Debugger Port
                                    <input type="text" name="cml_port"
                                           value="<?= Framework::$app["LAYER"]["debbger_port"]; ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <hr />
                                <h5> Other Configurations</h5>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-6 columns">
                                <label>Max CPU Usage (MB)
                                    <input type="text" name="max_cpu"
                                           value="<?= Framework::$app["LAYER"]["max_cpu"]; ?>"/>
                                </label>
                            </div>
                            <div class="large-6 columns">
                                <label>Max Memory Usage (MB)
                                    <input type="text" name="max_mem"
                                           value="<?= Framework::$app["LAYER"]["max_mem"]; ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-8 columns">
                                <br/>
                            </div>
                            <div class="large-4 columns">
                                <br/>
                                <input type="submit" class="button small primary radius" style="width: 100%;" value="Save"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="large-4 columns">
                <div class="panel callout">
                    <h5>Warning</h5>

                    <p>Be careful what you change by changing the variables of the system may result in
                        poor system functionality.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="pauseConsole" class="reveal-modal" tabindex="-1" role="dialog" aria-labelledby="PauseConsole"
     aria-hidden="true" data-reveal>
    <h5 id="PauseConsole">Server Console Raw Code</h5>

    <div class="row">
        <div class="large-1 columns"></div>
        <div class="large-10 columns">
            <textarea id="paused_console" class="form-control console" readonly="readonly"></textarea>
        </div>
        <div class="large-1 columns"></div>
    </div>
    <a class="close-reveal-modal">&#215;</a>
</div>
<script type="text/javascript">
    var socket, ctx, cty, memoryChartData, cpuChartData;
    $(document).ready(
        function () {
            $("#sidebar_links").find("a[href='/node/index']").addClass('active');
            url = 'http://<?= (Framework::$app["LAYER"]["cml_ip"] . ':' . Framework::$app["LAYER"]["cml_port"]); ?>';
            socket = io.connect(url);
            socket.on(
                'connect', function () {
                    jQuery("#socket_error").css('display', 'none');
                    console.log("[io] Connected on Server");
                }
            );
            socket.on(
                'connect_error', function (obj) {
                    jQuery("#socket_error").css('display', 'block');
                    console.log("[io] Connecting " + obj);
                }
            );
            socket.on(
                'error', function (obj) {
                    console.log("[io] " + obj);
                }
            );
            socket.on(
                'process', function (data) {
                    var currentTime = new Date();
                    memoryChart.addData([parseInt(data.process.memory / (1024 * 1024))], "");
                    memoryChart.removeData();
                    if (<?= Framework::$app["LAYER"]["max_cpu"]; ?> >
                    0
                    )
                    cpuChart.addData([(data.process.cpu / <?= Framework::$app["LAYER"]["max_cpu"]; ?> ) * 100], "");
                    else
                    cpuChart.addData([data.process.cpu], "");
                    cpuChart.removeData();
                }
            );
            socket.on(
                'query', function (data) {
                    if ($("#players_notice").is(":visible")) {
                        $("#players_notice").hide();
                        $("#toggle_players").show();
                    }
                    if (data.query.players.length !== 0) {
                        $("#toggle_players").html("");
                        $.each(
                            data.query.players, function (id, d) {
                                console.log(d);
                                $.each(
                                    d, function (n, name) {
                                        $("#toggle_players").append('<img data-toggle="tooltip" src="http://i.fishbans.com/helm/' + name + '/32" title="' + name + '" style="padding: 0 2px 6px 0;"/>');
                                    }
                                );
                            }
                        );
                    }
                    else {
                        $("#toggle_players").html('<p class="text-muted">No players are currently online.</p>');
                    }
                    $("img[data-toggle='tooltip']").tooltip();
                }
            );
            $('a[data-toggle="tab"]').on(
                'shown.bs.tab', function (e) {
                    $('#live_console').scrollTop($('#live_console')[0].scrollHeight - $('#live_console').height());
                }
            );
            socket.on(
                'console', function (data) {
                    $("#live_console").val($("#live_console").val() + data.l);
                    $('#live_console').scrollTop($('#live_console')[0].scrollHeight - $('#live_console').height());
                }
            );
            ctx = $("#memoryChart").get(0).getContext("2d");
            cty = $("#cpuChart").get(0).getContext("2d");
            memoryChartData = {
                labels: ["", "", "", "", "", "", "", "", "", ""],
                datasets: [{
                    fillColor: "#ccc",
                    strokeColor: "rgba(0,0,0,0)",
                    highlightFill: "#666",
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }]
            };
            cpuChartData = {
                labels: ["", "", "", "", "", "", "", "", "", ""],
                datasets: [{
                    fillColor: "#ccc",
                    strokeColor: "rgba(0,0,0,0)",
                    highlightFill: "#666",
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }]
            };
            memoryChart = new Chart(ctx).Bar(
                memoryChartData,
                {
                    animation: true,
                    showScale: true,
                    barShowStroke: false,
                    scaleOverride: false,
                    tooltipTemplate: "",
                    barValueSpacing: 2,
                    barStrokeWidth: 1,
                    scaleShowGridLines: false
                }
            );
            cpuChart = new Chart(cty).Bar(
                cpuChartData,
                {
                    animation: true,
                    showScale: true,
                    barShowStroke: false,
                    scaleOverride: false,
                    tooltipTemplate: "",
                    barValueSpacing: 2,
                    barStrokeWidth: 1,
                    scaleShowGridLines: false
                }
            );
            $("#pause_console").click(
                function () {
                    $("#paused_console").val();
                    $("#paused_console").val($("#live_console").val());
                }
            );
            $("#console_command").submit(
                function (event) {
                    event.preventDefault();
                    var ccmd = $("#ccmd").val();
                    if (ccmd != "") {
                        $("#sending_command").html('<i class="fa fa-refresh fa-spin"></i>').addClass('disabled');
                        $.ajax(
                            {
                                type: "POST",
                                headers: {"X-Access-Token": "{{ server.gsd_secret }}"},
                                url: 'http://{{ node.ip }}:{{ node.gsd_listen }}/gameservers/{{ server.gsd_id }}/console',
                                timeout: 5000,
                                data: {command: ccmd},
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $("#sc_resp").html('Unable to process your request. Please try again.').fadeIn().delay(5000).fadeOut();
                                    $("#sending_command").removeClass('disabled');
                                    $("#sending_command").html('&rarr;');
                                    $("#ccmd").val('');
                                },
                                success: function (data) {
                                    $("#sending_command").removeClass('disabled');
                                    $("#sending_command").html('&rarr;');
                                    $("#ccmd").val('');
                                    if (data != "") {
                                        $("#sc_resp").html(data).fadeIn().delay(5000).fadeOut();
                                    }
                                }
                            }
                        );
                    }
                }
            );
            var can_run = true;
            $("#kill_proc").click(
                function () {
                    var killConfirm = confirm("WARNING: This operation will not save your server data gracefully. You should only use this if your server is failing to respond to stops.");
                    if (killConfirm) {
                        $.ajax(
                            {
                                type: "GET",
                                headers: {"X-Access-Token": "{{ server.gsd_secret }}"},
                                url: 'http://{{ node.ip }}:{{ node.gsd_listen }}/gameservers/{{ server.gsd_id }}/kill',
                                timeout: 5000,
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $("#pw_resp").attr('class', 'alert alert-danger').html('Unable to process your request. Please try again. (' + errorThrown + ')').fadeIn().delay(5000).fadeOut();
                                    return false;
                                },
                                success: function (data) {
                                    if (data == "ok") {
                                        $("#pw_resp").attr('class', 'alert alert-success').html("Server has been killed successfully.").fadeIn().delay(5000).fadeOut();
                                        return false;
                                    }
                                }
                            }
                        );
                    }
                }
            );
            $(".poke").click(
                function () {
                    var command = $(this).attr("id");
                    if (command == 'off') {
                        uCommand = 'Stopping';
                    }
                    else {
                        uCommand = 'Restarting';
                    }
                    if (can_run === true) {
                        can_run = false;
                        $(this).append(' <i class="fa fa-refresh fa-spin"></i>');
                        $(this).toggleClass('disabled');
                        $.ajax(
                            {
                                type: "GET",
                                headers: {"X-Access-Token": "{{ server.gsd_secret }}"},
                                url: 'http://{{ node.ip }}:{{ node.gsd_listen }}/gameservers/{{ server.gsd_id }}/off',
                                timeout: 5000,
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $("#pw_resp").attr('class', 'alert alert-danger').html('Unable to process your request. Please try again. (' + errorThrown + ')').fadeIn().delay(5000).fadeOut();
                                    $("#off").removeClass('disabled');
                                    $("#off").html('Stop');
                                    $("#restart").removeClass('disabled');
                                    $("#restart").html('Restart');
                                    can_run = true;
                                    return false;
                                },
                                success: function (data) {
                                    if (data == "ok") {
                                        $("#pw_resp").attr('class', 'alert alert-success').html("Server has been stopped successfully.").fadeIn().delay(5000).fadeOut();
                                        can_run = true;
                                        if (command == "restart") {
                                            setTimeout(
                                                function () {
                                                    start_server();
                                                }, 5000
                                            )
                                        }
                                        $("#off").removeClass('disabled');
                                        $("#off").html('{{ lang.string_stop }}');
                                        return false;
                                    }
                                }
                            }
                        );
                    }
                    else {
                        return false;
                    }
                }
            );
            $("#on").click(
                function () {
                    start_server();
                }
            );
            function start_server() {
                if (can_run === true) {
                    can_run = false;
                    $("#restart").removeClass('disabled');
                    $("#restart").html('{{ lang.string_restart }}');
                    $("#on").append(' <i class="fa fa-refresh fa-spin"></i>');
                    $("#on").toggleClass('disabled');
                    $.ajax(
                        {
                            type: "POST",
                            url: "/node/ajax/console/power",
                            timeout: 5000,
                            error: function (jqXHR, textStatus, errorThrown) {
                                $("#pw_resp").attr('class', 'alert alert-danger').html('{{ lang.node_ajax_generic_error }} (' + errorThrown + ')').fadeIn().delay(5000).fadeOut();
                                $("#on").removeClass('disabled');
                                $("#on").html('Start');
                                can_run = true;
                                return false;
                            },
                            success: function (data) {
                                if (data == "ok") {
                                    $("#live_console").val("Server is starting...\n");
                                    $("#pw_resp").attr('class', 'alert alert-success').html("{{ lang.node_console_ajax_server_started }}").fadeIn().delay(5000).fadeOut();
                                    can_run = true;
                                }
                                else {
                                    $("#pw_resp").attr('class', 'alert alert-danger').html(data).fadeIn().delay(5000).fadeOut();
                                    can_run = true;
                                }
                                $("#on").toggleClass('disabled');
                                $("#on").html('{{ lang.string_start }}');
                            }
                        }
                    );
                }
                else {
                    return false;
                }
            }
        }
    );
</script>
