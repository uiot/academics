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
<body
    style="background: url(<?= link_to('/public/images/6.jpg'); ?>);background-size: cover;background-position-y: -40px;">
<div
    style="width: 100%;background: -webkit-radial-gradient(center, ellipse cover, rgba(255,255,255,0.4) 1%,rgba(255,255,255,0.01) 100%);height: 100%;position: absolute;">
    <img src="<?php echo link_to('/public/images/logo_small_transparent.png'); ?>"
         style="top: 10px;left: 10px;position: absolute;"></div>
<?= Framework::Render_Content(); ?>
<?= $footer->return_footer(); ?>
</body>
</html>
