<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="<?= App::$instance->opt->doctype; ?>"/>
    <title><?= App::$instance->opt->title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= CORE_REPOSITORY_HTTP_PATH ?>css/build/core.css"/>
    <script type="text/javascript" src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/build/core.js"></script>
    <!--        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/core.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="js/jquery-timepicker/jquery-ui-timepicker-addon.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/bootstrap-switch.min.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/search/search.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="css/fullcalendar.css"/>-->
    <!--    <link rel="stylesheet" type="text/css" href="css/custom-menu.css"/>-->
    <!--        <link rel="stylesheet" type="text/css" href="js/notify/css/bootstrap-notify.css"/>-->

<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery/jquery-1.11.0.min.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/support/support.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery/jquery-ui-1.10.3.custom.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery-timepicker/jquery-ui-timepicker-addon.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery/jquery.timepicker.min.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/bootstrap.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/modals/Modal.class.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/search/core.class.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/search/clicker.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/core.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/notify/js/soundmanager2-nodebug-jsmin.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/notify/js/bootstrap-notify.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/production/fancybox/source/jquery.fancybox.pack.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/bootstrap-switch.min.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery.fancybox.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/lib/moment.min.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/fullcalendar.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/ru.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery/spinner.js"></script>-->
<!--            <script type="text/javascript" src="--><?//= CORE_REPOSITORY_HTTP_PATH ?><!--js/jquery/spin.jquery.plugin.js"></script>-->

    <!--    <link rel="stylesheet" type="text/css" href="js/color-picker/css/pick-a-color-1.2.3.min.css"/>-->
    <!--    notices-->
    <!--[if lt IE 9]>
    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/polyfills/html5shiv.js"></script>
    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/polyfills/html5shiv-printshiv.js"></script>
    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/respond.min.js"></script>
    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/polyfills/console-polyfill.js"></script>

    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/polyfills/es5-shim.js"></script>
    <script src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/polyfills/es5-sham.js"></script>
    <![endif]-->
    <!--[if lte IE 8]>
    <script language="javascript" type="text/javascript" src="<?= CORE_REPOSITORY_HTTP_PATH ?>js/flot/excanvas.min.js"></script><![endif]-->
    <!--[if IE 8 ]>
    <script>
        $(document).ready(function () {
            var $style;
            $style = $('<style type="text/css">:before,:after{content:none !important}</style>');
            $('head').append($style);
            return setTimeout((function () {
                return $style.remove();
            }), 0);
        });
    </script>
    <![endif]-->
</head>
<div id="center" style="z-index: 6666; position:fixed;top:50%;left:50%"></div>
<?php
//if admin add notice listener
if (Auth::isAdmin(App::$instance))
    ST::setUserJs("notify/js/notify.puller.js");
ST::setVarPhptoJS(HTTP_PATH, 'core_http_path');
ST::setVarPhptoJS(HTTP_BASE_PATH, 'base_http_path');
ST::setVarPhptoJS(str_replace("Etc/", '', App::$instance->opt->timezone), 'time_zone');
Auth::isLogged(App::$instance) ? ST::setVarPhptoJS(Auth::getMyRole(App::$instance), "Auth") : ST::setVarPhptoJS("0", "Auth");
