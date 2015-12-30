<?php

class Log_controller extends Controller
{
    use sorterTrait;

    public function index($eventType = false)
    {
        Auth::isAdminOrDie(App::$instance);
        if (!$eventType) {
            $logList = LogRecord::take($this->getQlimit(50))
                ->skip($this->getQOffset())
                ->orderBy($this->getQOrder(), $this->getQVector())
                ->with("user")
                ->get();
        } else {
            $logList = LogRecord::where('event_type', $eventType)
                ->take($this->getQlimit(50))
                ->skip($this->getQOffset())
                ->orderBy($this->getQOrder(), $this->getQVector())
                ->with("user")
                ->get();
        }
        //define event type
        foreach ($logList as $log) {
            $log->humanized = new stdClass();
            $log->humanized->event_type = $this->defineLogType($log->event_type);
        }

        $eventType = $this->defineLogType($eventType);
        $recordsCount = LogRecord::all()->count();
        //pages
        $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(50), 'route');

        $this->render("logs/index", compact('logList', 'pages', 'eventType'));
    }

    function defineLogType($event_type)
    {
        $result = '';
        switch ($event_type) {
            case(LOG_USER_LOGIN):
                $result = "USER_LOGIN";
                break;
            case(LOG_USER_LOGOUT):
                $result = "USER_LOGOUT";
                break;
            case(LOG_VKSWS_CREATED):
                $result = "LOG_VKSWS_CREATED";
                break;
            case(LOG_VKSWS_UPDATED):
                $result = "LOG_VKSWS_UPDATED";
                break;
            case(LOG_VKSWS_DELETED):
                $result = "LOG_VKSWS_DELETED";
                break;
            case(LOG_VKSNS_CREATED):
                $result = "LOG_VKSNS_CREATED";
                break;
            case(LOG_VKSNS_UPDATED):
                $result = "LOG_VKSNS_UPDATED";
                break;
            case(LOG_VKSNS_DELETED):
                $result = "LOG_VKSNS_DELETED";
                break;

            case(LOG_ADMIN_LOGIN):
                $result = "LOG_ADMIN_LOGIN";
                break;
            case(LOG_ADMIN_LOGOUT):
                $result = "LOG_ADMIN_LOGOUT";
                break;
            case(LOG_VKS_CODE_SAVED):
                $result = "LOG_VKS_CODE_SAVED";
                break;
            case(LOG_VKSWS_RELATION_CREATED):
                $result = "LOG_VKSWS_RELATION_CREATED";
                break;
            case(LOG_VKSWS_RELATION_APPROVED):
                $result = "LOG_VKSWS_RELATION_APPROVED";
                break;
            case(LOG_VKSWS_RELATION_DECLINED):
                $result = "LOG_VKSWS_RELATION_DECLINED";
                break;
            case(LOG_MAIL_SENDED):
                $result = "LOG_MAIL_SENDED";
                break;
            case(LOG_POINT_CREATED):
                $result = "LOG_POINT_CREATED";
                break;
            case(LOG_POINT_UPDATED):
                $result = "LOG_POINT_UPDATED";
                break;
            case(LOG_POINT_DELETED):
                $result = "LOG_POINT_DELETED";
                break;
            case(LOG_CONFIG_CHANGE):
                $result = "LOG_CONFIG_CHANGE";
                break;
            case(LOG_OTHER_EVENTS):
                $result = "LOG_OTHER_EVENTS";
                break;
            case(LOG_USER_REGISTER):
                $result = "LOG_USER_REGISTER";
                break;
            case(LOG_SECURITY):
                $result = "LOG_SECURITY";
                break;
            case(LOG_ADMIN_SCHEDULE):
                $result = "LOG_ADMIN_SCHEDULE";
                break;

        }
        return $result;
    }
}