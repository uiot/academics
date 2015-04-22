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
<div class="nope obese"></div>
<div class="row">
    <div class="large-3 columns"><br/></div>
    <div class="large-6 columns">
        <h5><i class="fa fa-exclamation-triangle"></i> Error</h5>

        <div data-alert class="alert-box alert">
            <?= Framework::$app["error"]["message"]; ?>
        </div>
    </div>
    <div class="large-3 columns"><br/></div>
</div>
</div>
<?= $footer->return_footer(); ?>
</body>
</html>
