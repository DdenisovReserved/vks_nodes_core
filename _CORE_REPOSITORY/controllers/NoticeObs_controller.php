<?php
use Symfony\Component\Process\Exception\LogicException;

class NoticeObs_controller extends Controller
{

    public function pull()
    {
        if (!Auth::isLogged(App::$instance)) die(json_encode([])); // not auth
        //set time barrier
        if (!$this->isCanBePulled()) die(json_encode([])); // not time come

        $startThere = date_create()->modify("-120 seconds");
        $readed = $this->sesRead();

        //define last access time
        $this->putLastRequestTime();
       
        //get notices
        $notifications =
            Notification::where("created_at", ">", $startThere)
                ->where("created_at", "<", date_create())
                ->whereNotIn("id", $readed['filtered'])
                ->whereIn("to", [0, App::$instance->user->id])
                ->take(5)
                ->get();

        //humanize
        if (count($notifications)) {
            foreach ($notifications as $notice) {
                $notice->humanized = new stdClass();
                $notice->humanized->created_at = date_create($notice->created_at)->format("d.m.Y H:i:s");
            }
        }

        //mark as collected
        if (count($notifications)) {
            foreach ($notifications as $notice)
                $readed['raw'][] = [$notice->id, date_create()->format("Y-m-d H:i:s")];
            $this->sesWrite($readed['raw']);
        }

        if (ST::isAjaxRequest())
            print json_encode($notifications);
        else
            return $notifications;


    }

    static public function put($message, $alarm = 0, $type = NOTIFICATION_ALL, $to = 0)
    {

        Notification::create([
            'message' => $message,
            'type' => $type,
            'to' => $to,
            'alarm' => $alarm
        ]);

    }

    private function sesRead()
    {

        $sesName = md5(App::$instance->main->appkey . App::$instance->user->login . '42');
        $result = [];
        if (isset($_SESSION[$sesName]) && count($_SESSION[$sesName]) != 0) {

            $readSes = $_SESSION[$sesName];
            //slice stack to 100 elements when it grown over 200
            if (count($readSes) > 200) {
                $readSes = array_slice($readSes, 100);
            }


            foreach ($readSes as $readed) {
                $result['filtered'][] = $readed[0];
            }
            $result['raw'] = $readSes;
        } else {
            $result['filtered'] = [];
            $result['raw'] = [];
        }


        return $result;
    }

    private function sesWrite(array $data)
    {


        $sesName = md5(App::$instance->main->appkey . App::$instance->user->login . '42');

        $_SESSION[$sesName] = $data;

    }

    private function isCanBePulled()
    {
        $now = date_create()->getTimestamp();
        return (($now - $this->getLastRequestTime()) > 20) ? true : false;
    }

    private function getLastRequestTime()
    {
        if (!isset($_SESSION[$this->getSesNameforLastAccess()])) {
            $this->putLastRequestTime();
        }

        return $_SESSION[$this->getSesNameforLastAccess()];
    }

    private function putLastRequestTime()
    {
        if (!isset($_SESSION[$this->getSesNameforLastAccess()])) {
            $_SESSION[$this->getSesNameforLastAccess()] = Null;
        }

        $_SESSION[$this->getSesNameforLastAccess()] = date_create()->getTimestamp();

    }

    private function getSesNameforLastAccess()
    {
        return
            md5(App::$instance->main->appkey . App::$instance->user->login . '42 for last access');
    }

    public function sesFlush()
    {

        $sesName = md5(App::$instance->main->appkey . App::$instance->user->login . '42');

        $_SESSION[$sesName] = [];

    }

    public function test()
    {
        Notification::create([
            'message' => "Создана новая ВКС <a target='_blank' href='" . ST::route("Vks/show/512") . "'>#512</a>",
            'type' => NOTIFICATION_VKS_ACTION,
        ]);
    }
}