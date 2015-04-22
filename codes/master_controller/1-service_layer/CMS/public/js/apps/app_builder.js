/**
 * Created with PhpStorm.
 * User: Hiro Ferreira
 * Email: hiro.ferreira@gmail.com
 * Date: 13/04/15
 * Time: 15:08
 *
 */

var app_builder;
jQuery(document).ready(function () {
    app_builder = new AppBuilder("app_canvas","save_app","app_elements",12,9);
    app_builder.start();
});

var AppBuilder = function(canvas_id,save_button_id,menu_elements_id,grid_rows,grid_cols){

    var app_builder = {
        //holds if app is in edition mode
        edition_mode : false,

        //Id to give to next element in canvas
        next_element_id:0,

        //Element id prefix
        element_id_prefix:'canvas-element-',

        //Holds elements in canvas in order to give them unique id.
        elements: new Array(),

        //HTML element properties to hold elements
        canvas:{
            //canvas div html element id
            id:'',
            //width, in pixels, of canvas
            width:0,
            //height, in pixels, of canvas
            height:0,
            //distance that canvas stays from browser page top left in x and y.
            offset:{
                x:0,
                y:0
            },
            //HTML id of element responsible for saving app
            save_button_id: '',
            //HTML id of menu elements id
            menu_elements_id: ''
        },

        //grid within canvas to adjust elements position
        grid:{
            //number of vertical spaces in canvas grid
            rows:0,
            //number of horizontal spaces in canvas grid
            cols:0,
            //width, in pixels, of a cell
            cell_width:0,
            //height, in pixels, of a cell
            cell_height:0
        },

        start:function(){
            //prepares grid and protects it from resizes
            app_builder.resize_protection();
            window.onresize = function () {
                app_builder.resize_protection();
            }

            //prepares itens to be draggable
            app_builder.prepare_itens_to_be_draggable();

            //sets up save button
            app_builder.set_save_button();

            //disables menu itens "select"
            jQuery(app_builder.canvas.menu_elements_id+" ul, "+app_builder.canvas.menu_elements_id+"li").disableSelection();

            //draw grids
            app_builder.draw_grids();

            //prepare element properties to change values "on the fly"
            app_builder.prepare_properties();

        },

        prepare_properties: function(){
            jQuery(".element-property").livequery(function(){
               jQuery(this).change(function(e,key){
                   var property = jQuery(this);
                   var canvas_element = jQuery("#"+property.closest('form').attr('element-id')).first();
                   canvas_element.attr(property.attr("property"),property.val());
                   console.log('setting '+ property.attr("property")+" to "+ property.val());

                   var canvas_element = jQuery("#"+property.closest('form').attr('element-id')).first();
                   var col = canvas_element.attr('actual-col');
                   var row = canvas_element.attr('actual-row');
                   app_builder.redraw_element(canvas_element,col,row);
                   });
            });

        },

        draw_grids:function(){
            var canvas = jQuery(app_builder.canvas.id);
            canvas.find(".app-grid").remove();
            var distance_from_top = 0;
            var distance_from_left = 0;
            for(var actual_row =  1;actual_row<=app_builder.grid.rows;actual_row++){
                distance_from_top = (actual_row-1)* app_builder.grid.cell_height;
                for(var actual_col = 1;actual_col<=app_builder.grid.cols;actual_col++){
                    distance_from_left = (actual_col-1)* app_builder.grid.cell_width;
                    canvas.append('<div class="app-grid" id="app-canvas-grid-'+actual_row+'-'+actual_col+'" style="left:'+distance_from_left+'px;top:'+distance_from_top+'px;height:'+app_builder.grid.cell_height+'px;width:'+app_builder.grid.cell_width+'px;"/>');
                }

            }
        },

        set_save_button:function(){
            jQuery(app_builder.canvas.save_button_id).click(function (e) {
                //if it is in edition mode, change it to save mode
                if (app_builder.edition_mode) {
                    app_builder.set_canvas_droppable(false);
                    app_builder.drag_element_from_menu(false);
                    app_builder.drag_element_inside_canvas(false);
                    //Sort(true);
                    jQuery(this).html("Edit App");
                    jQuery(app_builder.canvas.menu_elements_id).slideUp();
                    app_builder.edition_mode = false;
                }

                //if it is not in edition mode, change it to edition mode
                else {
                    app_builder.set_canvas_droppable(true);
                    app_builder.drag_element_from_menu(true);
                    app_builder.drag_element_inside_canvas(true);
                    //Sort(false);
                    jQuery(this).html("Save App");
                    jQuery(app_builder.canvas.menu_elements_id).slideDown();
                    app_builder.edition_mode = true;
                }
            });
        },

        resize_protection:function(){
            //Gets canvas width and height in pixels
            app_builder.canvas.width = jQuery(app_builder.canvas.id).innerWidth();
            app_builder.canvas.height = jQuery(app_builder.canvas.id).innerHeight();

            //Calculates width and height of a cell in grid
            app_builder.grid.cell_width = app_builder.canvas.width / app_builder.grid.cols;
            app_builder.grid.cell_height = app_builder.canvas.height / app_builder.grid.rows;

            //Gets canvas of set related to window
            app_builder.canvas.offset = jQuery(app_builder.canvas.id).offset();

            //Redraw all itens in canvas to fit new cell size
            app_builder.redraw_elements();
            app_builder.draw_grids();
        },

        redraw_elements:function(){
            jQuery(app_builder.canvas.id+" .app_builder_canvas_element").each(function(){
                var col = jQuery(this).attr('actual-col');
                var row = jQuery(this).attr('actual-row');
                app_builder.redraw_element(jQuery(this),col,row);
            });
        },

        redraw_element:function(element,col,row){
            //sanitize args
            col = parseInt(col);
            row = parseInt(row);
            if(col)

            //calculates element position, based on row and col, in window.
            var x = (col-1)*app_builder.grid.cell_width;
            var y = (row-1)*app_builder.grid.cell_height;
            var width = (jQuery(element).attr('cols'))*app_builder.grid.cell_width;
            var height = (jQuery(element).attr('rows'))*app_builder.grid.cell_height;

            //positions element in screen
            jQuery(element).css("left", x);
            jQuery(element).css("top", y);
            jQuery(element).css("position", "absolute");
            jQuery(element).css('width',width);
            jQuery(element).css('height',height);
            jQuery(element).attr("actual-col",col);
            jQuery(element).attr("actual-row",row);

        },

        prepare_itens_to_be_draggable:function(){
            jQuery(".app_element.grid_model_element").draggable({disabled: true});
            jQuery(".app_element.app_builder_canvas_element").draggable({disabled: true});
            jQuery(app_builder.canvas.id).droppable({disabled: true});
        },

        set_canvas_droppable:function(enabled){
            jQuery(app_builder.canvas.id).droppable({
                disabled: !enabled,
                tolerance: 'pointer',
                drop: app_builder.on_element_drop
            });
        },

        on_element_drop:function (event, element) {
            //gets element from drag plugin, which is hold in .helper
            var el = jQuery(element.helper).first();

            //check if it has already been added to canvas, case it does, move it in canvas.
            if (el.hasClass('app_builder_canvas_element')) {
                app_builder.move_element_inside_canvas(el);
            }

            //check if it has not been added to canvas, case it did not, add it to canvas.
            else if (el.hasClass('grid_model_element')) {
                var el_id = app_builder.add_new_element_to_canvas(el);
                app_builder.move_element_inside_canvas(jQuery("#"+el_id));
            }
        },


        move_element_inside_canvas:function(element) {
            app_builder.snap_element_to_grid(element);
            app_builder.resize_element(element);
        },

        add_new_element_to_canvas: function(element) {
            //creates app and clone content
            var item = new AppBuilderElement();
            item.copy_from_menu_item(element);

            //adds element to local buffer
            var el_id = app_builder.element_id_prefix + app_builder.next_element_id;

            item.id = el_id;
            app_builder.next_element_id++;

            //Appends it to canvas
            jQuery(app_builder.canvas.id).append(item.get_html());
            app_builder.drag_element_inside_canvas(true);
            //prepares to show element configs on click
            jQuery("#"+el_id).click(function(){
                app_builder.on_canvas_element_click(jQuery(this));
            });
            jQuery("#"+el_id).attr('canvas-highlighted','no');
            return el_id;
        },

        drag_element_from_menu: function(enabled) {
            //enables or disable menu
            jQuery(".app_element.grid_model_element").draggable({
                disabled: !enabled,
                cursor: 'move',
                connectToSortable: app_builder.canvas.menu_elements_id,
                helper: 'clone',
                revert: 'invalid'
            });
        },

        drag_element_inside_canvas:function(enabled) {
            jQuery(".app_element.app_builder_canvas_element").draggable({
                disabled: !enabled,
                cursor: 'move',
                opacity: 0.50,
                revert: 'invalid',
                refreshPositions: true,
                stack: ".app_builder_canvas_element",//makes last dragged element stay on top of the other(z-index)
                drag:function(event,element){
                    app_builder.highlight_element_grids(element.helper);
                },
                stop:function(event,element){
                    app_builder.unhighlight_element_grids();
                    app_builder.unhighlight_canvas_element();
                    jQuery(element.helper).click();
                },
                start:function(event,element){
                    app_builder.unhighlight_canvas_element();
                }
            });

        },
        unhighlight_element_grids:function(){
            jQuery('.app-grid').removeClass("highlighted_grid");
        },

        unhighlight_canvas_element:function(){
            jQuery(".app_builder_canvas_element").removeClass('highlighted_canvas_element');
        },
        highlight_canvas_element:function(element){
            jQuery(element).addClass('highlighted_canvas_element');
        },


        highlight_element_grids:function(element){

            //gets element offset in window
            var element_offset_in_window = {x: 0, y: 0};
            element_offset_in_window.x = jQuery(element).offset().left;
            element_offset_in_window.y = jQuery(element).offset().top;

            //gets canvas offset in window
            var canvas_offset_in_window = {x: 0, y: 0};
            canvas_offset_in_window.x = jQuery(app_builder.canvas.id).offset().left;
            canvas_offset_in_window.y = jQuery(app_builder.canvas.id).offset().top;

            //calculates element offset in canvas
            var element_offset_in_canvas = {x: -1, y: -1};
            element_offset_in_canvas.x = element_offset_in_window.x - canvas_offset_in_window.x;
            element_offset_in_canvas.y = element_offset_in_window.y - canvas_offset_in_window.y;
            if(element_offset_in_canvas.x <0 || element_offset_in_canvas.y <0 ) {
                element_offset_in_canvas.x = (element.attr('actual-col')-1)*app_builder.grid.cell_width;
                element_offset_in_canvas.y = (element.attr('actual-row')-1)*app_builder.grid.cell_height;
            }else if(element_offset_in_canvas.x> jQuery(app_builder.canvas.id).width()|| element_offset_in_canvas.y > jQuery(app_builder.canvas.id).height()){
                element_offset_in_canvas.x = (element.attr('actual-col')-1)*app_builder.grid.cell_width;
                element_offset_in_canvas.y = (element.attr('actual-row')-1)*app_builder.grid.cell_height;
            }
                //calculates elements under possible dragged element drop.
                tmp_el = element;
                var row = app_builder.get_element_row(element_offset_in_canvas.y)-1;
                var col = app_builder.get_element_col(element_offset_in_canvas.x)-1;
                var el_height = parseInt(jQuery(element).attr("rows"));
                var el_width = parseInt(jQuery(element).attr("cols"));

                //unhighlight previous highlighted grdi element
                app_builder.unhighlight_element_grids();

                //highlights elements under possible dragged element drop.
                for(var actual_row=row+1;actual_row<=(row+el_height);actual_row++){
                    for(var actual_col=col+1;actual_col<=(col+el_width);actual_col++){
                        jQuery('#app-canvas-grid-'+actual_row+'-'+actual_col+'').addClass("highlighted_grid");
                    }
                }



        },

        resize_element:function(element) {
            //calculates element width and height in pixels to obey grid cells
            var width = app_builder.grid.cell_width*jQuery(element).attr('cols');
            var height = app_builder.grid.cell_height*jQuery(element).attr('rows');

            //sets element height and width
            jQuery(element).css("height", height);
            jQuery(element).css("width", width);
        },

        get_element_row:function(element_y_offset){
            var offset = parseInt(element_y_offset/app_builder.grid.cell_height)+1;
            return offset;
        },
        get_element_col:function(element_x_offset){
            var offset = parseInt(element_x_offset/app_builder.grid.cell_width)+1;
            return offset;
        },
        snap_element_to_grid: function(element) {

            //gets element offset in window
            var element_offset_in_window = {x: 0, y: 0};
            element_offset_in_window.x = jQuery(element).offset().left;
            element_offset_in_window.y = jQuery(element).offset().top;

            //gets canvas offset in window
            var canvas_offset_in_window = {x: 0, y: 0};
            canvas_offset_in_window.x = jQuery(app_builder.canvas.id).offset().left;
            canvas_offset_in_window.y = jQuery(app_builder.canvas.id).offset().top;

            //calculates element offset in canvas
            var element_offset_in_canvas = {x: -1, y: -1};
            element_offset_in_canvas.x = element_offset_in_window.x - canvas_offset_in_window.x;
            element_offset_in_canvas.y = element_offset_in_window.y - canvas_offset_in_window.y;


            if(element_offset_in_canvas.x <0 || element_offset_in_canvas.y <0 ) {
                alert("Please, drop element inside canvas.");
                element_offset_in_canvas.x = (element.attr('actual-col')-1)*app_builder.grid.cell_width;
                element_offset_in_canvas.y = (element.attr('actual-row')-1)*app_builder.grid.cell_height;
            }else if(element_offset_in_canvas.x> jQuery(app_builder.canvas.id).width()|| element_offset_in_canvas.y > jQuery(app_builder.canvas.id).height()) {
                alert("Please, drop element inside canvas.");
                element_offset_in_canvas.x = (element.attr('actual-col')-1)*app_builder.grid.cell_width;
                element_offset_in_canvas.y = (element.attr('actual-row')-1)*app_builder.grid.cell_height;
            }

                //calculates element row and col
                var row = app_builder.get_element_row(element_offset_in_canvas.y);
                var col = app_builder.get_element_col(element_offset_in_canvas.x);

                app_builder.redraw_element(element,col,row);
        },

        on_canvas_element_click:function(jquery_element){
            if(!jQuery(jquery_element).hasClass('highlighted_canvas_element')) {
                app_builder.unhighlight_canvas_element();
                app_builder.highlight_canvas_element(jquery_element);

                var element = new AppBuilderElement();
                element.copy_from_canvas_item(jquery_element);

                var config_bar = jQuery("#element_configuration");
                config_bar.html("");
                config_bar.append('<div class="element-properties row"></div>');

                var el_conf = jQuery("#element_configuration .element-properties");
                el_conf.append(element.get_properties_html());

            }else{
                app_builder.unhighlight_canvas_element();

                var config_bar = jQuery("#element_configuration");
                config_bar.html('<h5>Elements Properties</h5>');
                config_bar.append('<p>Click on an element on canvas to edit it\'s properties!</p>');
            }
        }
    };

    app_builder.canvas.id = "#"+canvas_id;
    app_builder.grid.rows = grid_rows;
    app_builder.grid.cols = grid_cols;
    app_builder.canvas.save_button_id = "#"+save_button_id;
    app_builder.canvas.menu_elements_id = "#"+menu_elements_id;
    return app_builder;
};


var AppBuilderElement = function(){
    var abe = {
        id:'',
        type:'',
        rows:1,
        cols:1,
        color_bg:'#008CBA',
        color_text:'white',
        icon:'',
        click_action:'',
        actual_row:0,
        actual_col:0,
        html_class:'',
        html_tag:'',
        inner_html:'',

        copy_from_menu_item:function(menu_item){
            abe.copy_element(menu_item,'canvas-');
        },

        copy_from_canvas_item:function(canvas_item){
            abe.copy_element(canvas_item,'');
        },

        copy_element:function(item,prefix){
            abe.type =  jQuery(item).attr(prefix+'type');
            abe.html_class =  jQuery(item).attr(prefix+'class');
            abe.inner_html =  jQuery(item).attr(prefix+'default-html');
            abe.cols = jQuery(item).attr(prefix+'cols');
            abe.rows = jQuery(item).attr(prefix+'rows');
            abe.actual_col = jQuery(item).attr(prefix+'actual-col');
            abe.actual_row = jQuery(item).attr(prefix+'actual-row');
            abe.rows = jQuery(item).attr(prefix+'rows');
            abe.id = jQuery(item).attr(prefix+'id');

            abe.color_bg = jQuery(item).css('background');
            abe.color_text = jQuery(item).css('color');
        },

        get_properties_html:function(){
            var html = '<form onsubmit="return false;" class="" element-id="'+abe.id+'">';
            html+= "<h5>Element Properties (";
            html+= abe.get_form_subheader(abe.type);
            html+= ")</h5>";
            html+= abe.get_form_mainheader("Canvas");
            html += abe.get_form_input_row("Width",'cols',abe.cols);
            html += abe.get_form_input_row("Height",'rows',abe.rows);
            html += abe.get_form_input_row("Position-x",'actual-col',abe.actual_col);
            html += abe.get_form_input_row("position-y",'actual-row',abe.actual_row);
            html+= abe.get_form_mainheader("Layout");
            html += abe.get_form_input_row("Text",'cols',abe.inner_html);
            html += abe.get_form_input_row("Icon",'rows',abe.icon);
            html += abe.get_form_input_row("Text Color",'sdf',abe.color_text);
            html += abe.get_form_input_row("BG Color",'actual-row',abe.color_bg);
            html += abe.get_delete_button();
            html += "</form>";
            return html;
        },

        get_delete_button:function(){
            return'<button onclick="jQuery(\'#'+abe.id+'\').remove();jQuery(\'.element-properties\').html(\'<h5>Element Properties</h5><div><p>Click on an element on canvas to edit its properties!</p></div>\');">delete element</button>';
        },

        get_form_input_row:function(label,property,value){
            var row_html = '<div class="row">'+
                '<div class="large-12 columns">'+
                '<label>'+label+
                '<input class="element-property" type="text" property="'+property+'" value="'+value+'"/>'+
                '</label>'+
                '</div>'+
                '</div>';
            return row_html;
        },
        get_form_subheader:function(text){
          return '<b style="font-weight: normal;font-variant: small-caps;">'+text+'</b>'
        },
        get_form_mainheader:function(text){
            return '<h4>'+text+'</h4>'
        },
        get_html:function(){
            return "<div  type='" + abe.type + "' id='" + abe.id + "' cols='"+abe.cols+"' default-html=\""+abe.inner_html.replace(/"/g, '\\"')+"\" rows='"+abe.rows+"' class='" + abe.html_class + " app_element app_builder_canvas_element' " + abe.html_attributes + " >" + abe.inner_html + "</div>";
        }
    };
    return abe;
}