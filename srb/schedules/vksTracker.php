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

$now = date_create()->setTimezone(new DateTimeZone(NODE_TIME_ZONE));
$now->modify("+25 minutes");
$now->setTime($now->format('H'), $now->format('i'), 0);
$mskTime = clone($now);
//dump($now);
$mskTime->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone));
$CaNotes = new NotesCa_controller();
 if ($now->format('i') % 15 == 0) {
$events =

    Vks::where(function($query) use ($now) {
        $query->where('start_date_time', $now)->whereIn('status', [VKS_STATUS_APPROVED]);
    })->where(function($query) {
        $query->where('flag', 1)->orWhere('record_required', 1);
    })->get(['id', 'start_date_time', 'end_date_time', 'title','record_required', 'status', 'approved_by', 'owner_id', 'is_simple', 'flag'])
        ->toArray();

$CaEventsTmp = CAInsideParticipant::where('attendance_id', App::$instance->tbId)->with(['vks' => function ($query) use ($mskTime) {
    $query->where('start_date_time', $mskTime)
        ->whereIn('status', [VKS_STATUS_APPROVED]);
}])->get(['vks_id']);
//add to events container
$CaEvents = [];
foreach ($CaEventsTmp as $CAVks) {
    if ($CAVks->vks) {
        $CAVks->vks->fromCa = true;
        $CAVks->vks->tbFlag = $CaNotes->checkFlag($CAVks->vks->id);

        $CAVks->vks->start_date_time = $CAVks->vks->start_date_time instanceof DateTime ? $CAVks->vks->start_date_time : date_create($CAVks->vks->start_date_time);
        $CAVks->vks->end_date_time = $CAVks->vks->end_date_time instanceof DateTime ? $CAVks->vks->end_date_time : date_create($CAVks->vks->end_date_time);

        if ($CAVks->vks->tbFlag) {
            $CaEvents[] = $CAVks->vks;
        }
    }
}

$events = array_merge($events, $CaEvents);
//dump($events);
if (count($events))
    foreach ($events as $event) {

        if ($event['start_date_time']->getTimestamp() - $now->getTimestamp() <= 1200) {
            if (isset($event['fromCa'])) {
                NoticeObs_controller::put("Внимание! до начала важной ВКС в ЦА " . ST::linkToCaVksPage($event['id']) . " осталось менее 20 минут, старт в " . date_create($event['start_date_time'])->format("H:i"), 1);
            } else {
                $text = '';
                if ($event['flag']) {
                    $text .= 'важной';
                }
                if ($event['record_required']) {
                    if (strlen($text) > 0)
                        $text .= ', ';

                    $text .= ' требующей видеозаписи ';
                }

                NoticeObs_controller::put("Внимание! до начала {$text} ВКС " . ST::linkToVksPage($event['id']) . " осталось менее 20 минут, старт в " . $event['start_date_time']->format("H:i"), 1);
            }
//
        }
    }
 }
//find Vks with flag and start in 20 mins today


//end schedule here
//-----------------------------------------------------------------------------

