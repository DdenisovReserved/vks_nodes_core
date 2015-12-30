<?php
require_once('config/_definitions.php'); //load main node config
require_once(CORE_REPOSITORY_REAL_PATH.'config/_version.php'); //version
require_once(CORE_APP_PATH . 'config/_definitions.php'); //central planner Load
require_once('config/config.php'); //local config
require_once('config/autoloader.php'); //local autoloader
require_once(CORE_REPOSITORY_REAL_PATH.'vendor/autoload.php'); //vendor
$app = App::get_instance($params);
require_once('init.php');
$app->user = new Auth();
$app->node = MY_NODE;

//$getTb = Curl::get(ST::routeToCaApi('getTbId/'.TB_PATTERN));
//$getTb = json_decode($getTb, True);
//
//if ($getTb['status'] != 200) {
//    App::$instance->MQ->setMessage('Внимание: нет связи с Планировщиком ЦА, вы не получаете события с центрального сервера','danger');
//} else {
//    $app->tbId = $getTb['data'][0];
//}
$app->tbId = CAAttendance::where('name', 'like' ,'%'.TB_PATTERN.'%')->where('is_tb', 1)->first(['id'])->id;
if (!$app->tbId) {
    App::$instance->MQ->setMessage('Внимание: нет связи с Планировщиком ЦА, вы не получаете события с центрального сервера', 'danger');
}
header($app->opt->header);
date_default_timezone_set($app->opt->timezone);
//dump(App::$instance->capsule->getConnection()->getQueryLog());
$router = new Router();



