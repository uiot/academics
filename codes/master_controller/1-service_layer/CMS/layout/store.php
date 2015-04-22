<html>
<head>
    <?php

    /**
     * Header And Footer Loading
     */

    $header = new PageHeader();
    $footer = new PageFooter();
    echo $header->return_header();
    ?>
</head>
<style>::-webkit-scrollbar {
        width: 0px;
    }</style>
<body>
<div class="off-canvas-wrap" style="background: url(<?= link_to('/public/images/marquee-stars.svg'); ?>);"
     data-offcanvas>
    <div class="inner-wrap" style="min-height: 100%;">
        <nav class="tab-bar" style="height: 2.5rem;line-height: 2.5rem;z-index:1001;" id="Menu">
            <section class="left-small" id="Section2" style="height: 2.5rem;line-height: 2.5rem;">
                <a class="left-off-canvas-toggle menu-icon" style="height: 2.5rem;line-height: 2.5rem;"
                   href="javascript:void(0);"><span></span></a>
            </section>
            <section class="right tab-bar-section" style="right:auto;height: 2.5rem;line-height: 2.5rem;"
                     onclick="window.location.href='<?= link_to('/'); ?>'" onMouseOver="this.style.cursor='pointer'">
                <h1 class="title" style="font-weight:normal;font-size: 0.94444rem;line-height: 2.5rem;"><b>UIoT</b>
                    cms<span class="label regular" style="top:-2px;left:4px;">alpha</span></h1>
            </section>
            <section class="right top-bar-section" style="height: 2.5rem;line-height: 2.5rem;">
            </section>
        </nav>
        <aside class="left-off-canvas-menu" style="background: #FAFAFA;box-shadow: 1px 1px 7px gray;z-index: 1000;">
            <ul class="off-canvas-list main_menu">
                <li><label>Welcome, <b><?php $name = ucfirst(escape_text($_SESSION['u_real']));
                            echo " " . $name; ?></b></label></li>
            </ul>
            <div class="sidebar" style="padding: 10px 20px;">
                <h5>search something on the cms</h5>
                <input id="search_item" tabindex="1" id="autocomplete" type="search" placeholder="search on the cms"
                       autocomplete="off">
                <nav>
                    <h5 class="result_title" style="display:none;">search content result</h5>
                    <ul class="side-nav" id="result_content"></ul>
                    <h5>cms main menu</h5>
                    <ul class="side-nav" id="form_search">
                        <?php
                        $menu_items = new MenuItems();
                        $menu_items->add_label('Home', 'fa fa-home');
                        $menu_items->add_item(link_to("/home/index/"), 'fa fa-suitcase', '', 'Home', 'Home');
                        $menu_items->add_label('System', 'fa fa-sitemap');
                        $menu_items->add_item(link_to("/slavecontroller/list/"), 'fa fa-server', '', 'Slave Controllers', 'System');
                        $menu_items->add_item(link_to("/device/list/"), 'fa fa-server', '', 'Devices', 'System');
                        $menu_items->add_item(link_to("/service/list/"), 'fa fa-server', '', 'Services', 'System');
                        $menu_items->add_item(link_to("/action/list/"), 'fa fa-server', '', 'Actions', 'System');
                        $menu_items->add_item(link_to("/argument/list/"), 'fa fa-server', '', 'Arguments', 'System');
                        $menu_items->add_item(link_to("/statevariable/list/"), 'fa fa-server', '', 'State Variables', 'System');
                        $menu_items->add_label('Debugger', 'fa fa-cog');
                        $menu_items->add_item(link_to("/debugger/index/"), 'fa fa-terminal', '', 'Console', 'Debugger');
                        $menu_items->add_label('Store', 'fa fa-shopping-cart');
                        $menu_items->add_item(link_to("/store/index/"), 'fa fa-puzzle-piece', '', 'Store', 'Store');
                        $menu_items->add_item(link_to("/apps/list"), 'fa fa-cube', '', 'Apps', 'Store');
                        $menu_items->add_label('You', 'fa fa-user');
                        $menu_items->add_item(link_to("/users/list/"), 'fa fa-users', '', 'Users', 'You');
                        $menu_items->add_item(link_to("/login/logout/"), 'fa fa-power-off', '', 'Logout', 'You');
                        $menu_items->render_items();
                        ?>
                    </ul>
                </nav>
            </div>
        </aside>
        <section class="main-section">
            <?= Framework::Render_Content(); ?>
        </section>
    </div>
</div>
<?= $footer->return_footer(); ?>
</body>
</html>
