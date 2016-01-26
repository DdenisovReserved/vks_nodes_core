<?php
/*
 * track vks with flag, if 20 to start, put messages to all admins
 * run every 1 minutes
 *
 */
require_once('../../_OBSERVER/bot_const.php'); //bot constants
require_once('../config/bot_redefined_config.php'); //local bot config
require_once(CORE_APP_PATH . 'config/bot_redefined_config.php'); //central bot config
require_once('../config/autoloader.php'); //local autoloader
require_once('../config/config.php'); //local config
require_once(CORE_REPOSITORY_REAL_PATH.'vendor/autoload.php');
$app = App::get_instance($params);
require_once('../init.php');
$app->user = new Auth();
//ST::makeDebug($init->user);
//$init->MQ->setMessage($init->opt->pm);
$app->tbId = CAAttendance::where('name', 'like', '%' . TB_PATTERN . '%')->where('is_tb', 1)->first()->id;
header($app->opt->header);
date_default_timezone_set($app->opt->timezone);
//require_once('../init.php');

//start schedule here
//-----------------------------------------------------------------------------
$otlCalCtrl= new TechSupport_controller();
$otlCalCtrl->pullAndSendRequests();

//end schedule here
//-----------------------------------------------------------------------------

