<?php
define('MY_NODE', TB_SRB);
$params = array(
    'main' => [
        'appkey'=>sha1('code_here'), //set application key, need for encription
    ],
    'opt' => array(
        //also see base/app.twig templates
        'title' => 'Планировщик ВКС СРБ',
        'header' => 'Content-Type: text/html; Charset=utf-8',
        'timezone' => NODE_TIME_ZONE,
        'ca_timezone' => 'Etc/GMT-3',
        'doctype' => 'IE=Edge,chrome=1',
        'appHttpPath' => NODE_HTTP_PATH,
        'fizPath' => NODE_REAL_PATH,
        'pm' => null
    ),

    'db' => array(
        'host' => 'localhost',
        'dbname' => 'videoconf_node_srb',
        'user' => '',
        'pass' => '',
        'schema' => '',
        'prefix' => 'vcca_',
        'type' => 'mysql'

    ),
    'memcache' => array(
        'host' => '127.0.0.1',
        'port' => '6379',
    ),

);

