<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="<?= App::$instance->opt->doctype; ?>"/>
    <title><?= App::$instance->opt->title; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/core.css"/>
    <script type="text/javascript" src="js/jquery/jquery-1.11.0.min.js"></script>
    <!--    <script type="text/javascript" src="js/jquery/jquery-ui-1.10.3.custom.js"></script>-->
    <!--    <script type="text/javascript" src="js/jquery-timepicker/jquery-ui-timepicker-addon.js"></script>-->
    <!--    <script type="text/javascript" src="js/jquery/jquery.timepicker.min.js"></script>-->
    <script type="text/javascript" src="js/support/support.js"></script>
    <script type="text/javascript" src="js/production/bootstrap.min.js"></script>
    <?php
    $p = new SearchAssert();
    $p->init();
    ST::setUserJs('modals/Modal.class.js');
    ?>
    <script type="text/javascript" src="js/core.js"></script>

    <!--    notices must be removed from all access!-->
    <script type="text/javascript" src="js/notify/js/soundmanager2-nodebug-jsmin.js"></script>
    <script type="text/javascript" src="js/notify/js/bootstrap-notify.js"></script>
    <link rel="stylesheet" type="text/css" href="js/notify/css/bootstrap-notify.css"/>
    <!--    notices-->

    <!--    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>-->
    <link rel="stylesheet" type="text/css" href="js/jquery-timepicker/jquery-ui-timepicker-addon.css"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css"/>
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <!--    add fancybox    -->
    <script type="text/javascript" src="js/production/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="js/production/fancybox/source/jquery.fancybox.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="js/production/fancybox/source/jquery.fancybox.pack.js"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <link rel="stylesheet" href="js/production/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5"
          type="text/css" media="screen"/>


    <script type="text/javascript" src="js/jquery/bootstrap.file-input.js"></script>


    <!--    !add fancybox    -->
    <!-- add bootstrap checkbox change -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-switch.min.css"/>
    <script src="js/bootstrap-switch.min.js"></script>

</head>

<?php
//if admin add notice listener
if (Auth::isAdmin(App::$instance))
    ST::setUserJs("notify/js/notify.puller.js");
ST::setVarPhptoJS(HTTP_PATH, 'core_http_path');
ST::setVarPhptoJS(HTTP_BASE_PATH, 'base_http_path');
ST::setVarPhptoJS(str_replace("Etc/", '', App::$instance->opt->timezone), 'time_zone');
Auth::isAdmin(App::$instance) ? ST::setVarPhptoJS(Auth::getMyRole(App::$instance), "Auth") : ST::setVarPhptoJS("0", "Auth");