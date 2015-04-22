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
<body>
<?= Framework::Render_Content(); ?>
<?= $footer->return_footer(); ?>
</body>
</html>