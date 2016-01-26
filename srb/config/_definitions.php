<?php

$path_server_root = "c:/Users/Public/xampp/htdocs/";

$server_ip = "http://localhost:83/";

$core_ca_dir = "videoconf-ca-dev/";

$nodes_dir = "videoconf_nodes/";

$current_tb_dir = 'srb/';

define("CORE_REPOSITORY_REAL_PATH", $path_server_root . $nodes_dir ."_CORE_REPOSITORY/");

define("CORE_REPOSITORY_HTTP_PATH", $server_ip . $nodes_dir . "_CORE_REPOSITORY/");

define("CORE_APP_PATH", $path_server_root . $core_ca_dir);

define("TB_PATTERN", 'Среднерусский');

define("TB_NAME", 'СРБ');

define("NODE_REAL_PATH", $path_server_root . $nodes_dir . $current_tb_dir);

define("NODE_HTTP_PATH", $server_ip . $nodes_dir . $current_tb_dir);

define("NODE_PUBLIC_MESSAGE", "СРБ");

define('NODE_TIME_ZONE', 'Etc/GMT-3');




