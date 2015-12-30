<?php
ini_set("max_execution_time", 190);
require_once('config/_definitions.php'); //load main node config
require_once(CORE_REPOSITORY_REAL_PATH.'vendor/autoload.php');

use MatthiasMullie\Minify;
$minifier = new Minify\CSS();

$sourcePathArray = [
    'css/bootstrap.min.css',
    'css/core.css',
    'css/jquery-ui.css',
    'js/jquery-timepicker/jquery-ui-timepicker-addon.css',
    'css/jquery.timepicker.css',
    'css/jquery.fancybox.css',
    'css/bootstrap-switch.min.css',
    'css/search/search.css',
    'css/fullcalendar.css',
    'js/notify/css/bootstrap-notify.css',
    'css/custom-menu.css'
];

foreach ($sourcePathArray as $cssPath) {
    $minifier->add(CORE_REPOSITORY_REAL_PATH.$cssPath);
}

// save minified file to disk
$minifier->minify(CORE_REPOSITORY_REAL_PATH.'css/build/core.css');



$minifier = new Minify\JS();

$sourcePathArrayJS = [
    'js/jquery/jquery-1.11.0.min.js',
    'js/support/support.js',
    'js/jquery/jquery-ui-1.10.3.custom.js',
    'js/jquery-timepicker/jquery-ui-timepicker-addon.js',
    'js/jquery/jquery.timepicker.min.js',
    'js/bootstrap.js',
    'js/modals/Modal.class.js',
    'js/search/core.class.js',
    'js/search/clicker.js',
    'js/core.js',
    "js/notify/js/soundmanager2-nodebug-jsmin.js",
    "js/notify/js/bootstrap-notify.js",
    "js/production/fancybox/source/jquery.fancybox.pack.js",
    "js/bootstrap-switch.min.js",
    "js/jquery.fancybox.js",
    "js/lib/moment.min.js",
    "js/fullcalendar.js",
    "js/ru.js",
    'js/jquery/spinner.js',
    'js/jquery/spin.jquery.plugin.js'
];

foreach ($sourcePathArrayJS as $jsPath) {
    $minifier->add(CORE_REPOSITORY_REAL_PATH.$jsPath);
}

// save minified file to disk
$minifier->minify(CORE_REPOSITORY_REAL_PATH.'js/build/core.js');


