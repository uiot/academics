<html>
<head>
    <?php

    //adds page script tags
    Framework::$header->clear_scripts();
    Framework::$header->add_script("/public/js/vendor/jquery.js");
    Framework::$header->add_script("/public/js/vendor/livequery.js");

    //adds page style tags
    Framework::$header->clear_styles();
    Framework::$header->add_style("/public/css/styles.css");
    Framework::$header->add_style("/public/css/foundation.css");
    Framework::$header->add_style("/public/css/font-awesome.css");
    Framework::$header->add_style("/public/css/apps/app_builder.css");

    //prints header
    Framework::$header->print_html();
    ?>
</head>
<nav class="top-bar" data-topbar role="navigation" style="display: none;">
    <ul class="title-area">
        <li class="name">
            <h1><a href="#">UIoT App Builder</a></h1>
    </ul>

    <section class="top-bar-section">
        <ul class="right">
            <li><a href="#">Back</a></li>
        </ul>

        <ul class="left"></ul>
    </section>
</nav>
<?php

echo Framework::Render_Content();

//adds footer styles
Framework::$footer->clear_styles();
Framework::$footer->add_style("/public/css/styles.css");
Framework::$footer->add_style("/public/css/foundation.css");
Framework::$footer->add_style("/public/css/docs.css");

//adds footer scripts
Framework::$footer->clear_scripts();
Framework::$footer->add_script("/public/js/vendor/ui.js");

//prints footer
Framework::$footer->print_html();

?>
</body>
</html>
