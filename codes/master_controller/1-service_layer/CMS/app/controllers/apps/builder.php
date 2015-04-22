<div class="row r_a">
    <div class="small-2 columns l_a">
        <div class="hide-for-small">
            <div class="sidebar s_a">
                <h5>UIoT App Builder</h5>

                <a id="save_app" class="download button expand ">Edit App</a>
                <nav>
                    <ul class="side-nav" id="app_elements" style="display: none;">
                        <li class="heading">App Elements</li>
                        <li class="divider"></li>
                        <li>Drag elements to white panel at left to add them to your app.</li>
                        <li class="divider"></li>
                        <li><a class="app_element  grid_model_element"
                               canvas-tag="img"
                               canvas-type="image"
                               canvas-class=""
                               canvas-rows="3"
                               canvas-cols="2"
                               canvas-default-html="<img style='max-height:100%;' src='http://blogs.edweek.org/teachers/coach_gs_teaching_tips/Teaching%20To%20The%20Test.gif' \>">Image</a></li>
                        <li><a class="app_element  grid_model_element"
                               canvas-tag="a"
                               canvas-type="button"
                               canvas-attr="href='#'"
                               canvas-class="button"
                               canvas-rows="1"
                               canvas-cols="1"
                               canvas-default-html="Some Text">Button</a></li>
                        <li><a class="app_element grid_model_element">Panel</a></li>
                        <li><a class="app_element grid_model_element">Label</a></li>
                        <li><a class="app_element grid_model_element">Alert</a></li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="small-8 columns l_b">
        <div id="canvas_box">
            <div id="app_canvas">

            </div>
        </div>
    </div>
    <div class="small-2 columns l_c">
        <div class="sidebar s_b" id="element_configuration">
            <h5>Element Properties</h5>
            <p>Click on an element on canvas to edit it's properties!</p>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo link_to('/public/js/apps/app_builder.js'); ?>"></script>
