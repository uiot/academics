<div class="large-7 columns">
    <div data-alert class="alert-box alert" id="socket_error" style="padding-bottom:27px;">
        <div class="row">
            <div class="small-8 columns">
                <i class="fa fa-exclamation-triangle"></i> We were unable to connect to the main server, there may be
                network issues currently. This message will disappear when
                we are able to connect, you do not need to
                refresh.
            </div>
            <div class="yes">
                <a href="/debugger/index/" class="button tiny radius alert">Go to Server Panel</a>
            </div>
        </div>
    </div>
    <?php
    echo Framework::$app['tutorial'];
    $charts_tree = new Charts();
    echo $charts_tree->render_html();
    ?>
</div>
<div class="large-5 columns">
    <div class="panel callout">
        <h5><i class="fa fa-bullhorn"></i> Welcome</h5>

        <p>Below you can see a chained list of all controller, devices and related XMLs! Clik them to explore.</p>
    </div>
    <div class="panel" id="Section3">
        <h5><i class="fa fa-bar-chart"></i> See the Tree Graph</h5>

        <div class="row">
            <div class="small-8 columns">
                <p>You Can see The Slave Controllers, Devices, Services, Actions in a Tree Graph, Only clicking the
                    Button.</p>
            </div>
            <a href="#" class="button tiny secondary radius" data-reveal-id="tree_graph">Click Here</a>
        </div>
    </div>
    <div id="tree_graph" class="reveal-modal"
         style="max-width: 100%;width: 100%;max-height: 100%;height: 100%;top: 0px !important;border: none;padding-left: 0px;padding-right: 0px;border-radius: 0px;"
         data-reveal>
        <h2 style="margin-left: 30px;margin-top: -20px;">Tree Graph</h2>
        <canvas id="graph_canvas" style="border:none !important;"></canvas>
        <a class="close-reveal-modal" style="margin-top: 10px;">&#215;</a>
    </div>
</div>

<div id="linkModal" class="reveal-modal medium" data-reveal>
    <div id="linkContent"></div>
    <a class="close-reveal-modals">&#215;</a>
</div>

<script type="text/javascript">
    var url, socket;
    $(document).ready(
        function () {
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
        }
    );
    var theUI = JSON.parse('<?php echo json_encode($charts_tree->render_json ()); ?>');
</script>







