<?php
use Symfony\Component\HttpFoundation\Request;

class CalendarFeed_controller extends Controller
{

    public function feedMain()
    {

        $CaNotes = new NotesCa_controller();
        $start = $this->request->query->get('start');
        $end = $this->request->query->get('end');

        //get local events
        $cacheName = App::$instance->tbId . ".vks.events.calendar.{$start}.{$end}";
        $events = App::$instance->cache->get($cacheName);
//      dump($events);
        if (!$events) {
            $events =
                Vks::where('start_date_time', ">=", $start)
                    ->where('start_date_time', '<=', $end)
                    ->whereIn('status', [VKS_STATUS_PENDING, VKS_STATUS_APPROVED])
                    ->get();
            $cachedObj = new CachedObject($events, ['tag.' . $cacheName, "tag." . App::$instance->tbId . ".vks.events.calendar"]);
//            dump($cachedObj);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }
        //get events from central server
        if (Auth::isAdmin(App::$instance)) {
            //pull from central server=
            $getFromCA = Curl::get(ST::routeToCaApi("getVksWasInPeriodForTb/" . App::$instance->tbId . "/" . $start . "/" . $end));
            $getFromCA = json_decode($getFromCA);
//            dump($getFromCA->status);
            if ($getFromCA && $getFromCA->status == 200) {
                $getFromCA = $getFromCA->data;
            } else {
                $getFromCA = array();
            }
            //add to events container
//            dump($getFromCA);
            foreach ($getFromCA as $CAVks) {
                $CAVks->fromCa = true;
                $CAVks->tbFlag = $CaNotes->checkFlag($CAVks->id);
                $CAVks->isLinked = Vks::where('link_ca_vks_id', $CAVks->id)->count();
                $events[] = $CAVks;
            }
        }

        //!get event
        //event customization
        $events = $this->prepareEvents($events);
        //end foreach
        //!event customization


        print json_encode($events); //output


    }

    public function feedMainCount()
    {
        $vks = new Vks();
        $start = date_create($this->request->query->get('start'));
        $end = date_create($this->request->query->get('end'));
        $events = [];
        $db = App::$instance->capsule->getConnection();
        //get local events
        $cacheName = App::$instance->tbId . ".vks.events.calendar_counters.{$start->getTimestamp()}.{$end->getTimestamp()}";
        $events = App::$instance->cache->get($cacheName);

        if (!$events) {
            $sql = "SELECT COUNT(*) as counter, date, status
                FROM " . App::$instance->db->prefix . $vks->getTable() . "
                WHERE start_date_time >= '" . $start->setTime(0, 0)->format("Y-m-d H:i:s") . "'
                AND start_date_time <= '" . $end->setTime(23, 59)->format("Y-m-d H:i:s") . "'
                AND status in (" . VKS_STATUS_PENDING . ", " . VKS_STATUS_APPROVED . ")
                GROUP BY date, status";

            $raw_results = $db->select($db->raw($sql));
            $result = array();
            if (count($raw_results))
                foreach ($raw_results as $vksCounter) {
                    $formatDate = date_create($vksCounter['date'])->format("Y-m-d");

                    if ($vksCounter['status'] === VKS_STATUS_APPROVED) {
                        $result[$formatDate][VKS_STATUS_APPROVED] = $vksCounter['counter'];
                    } else {
                        $result[$formatDate][VKS_STATUS_PENDING] = $vksCounter['counter'];
                    }
                }

            while ($start < $end) {
                $formatDate = $start->format("Y-m-d");
                if (!array_key_exists($formatDate, $result)) {
                    $result[$formatDate][VKS_STATUS_APPROVED] = 0;
                    $result[$formatDate][VKS_STATUS_PENDING] = 0;
                } else {
                    //check not existed keys and fill them with zeros
                    if (!isset($result[$formatDate][VKS_STATUS_APPROVED]))
                        $result[$formatDate][VKS_STATUS_APPROVED] = 0;
                    if (!isset($result[$formatDate][VKS_STATUS_PENDING]))
                        $result[$formatDate][VKS_STATUS_PENDING] = 0;
                }
                $start->modify("+1 day");
            }

            foreach ($result as $date => $counters) {
                $events[] = array(
                    'start' => $date,
                    'php_date' => $date,
                    'counters' => $counters,
                );
            }

            //cache it
            $cachedObj = new CachedObject($events, ['tag.' . $cacheName, "tag." . App::$instance->tbId . ".vks.events.calendar_counters"]);
//            dump($cachedObj);
            App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);
        }

        print json_encode($events); //output
    }

    public function feedInPeriod($start, $end)
    {
        //get event
        $start = $start instanceof DateTime ? $start : date_create($start);
        $end = $end instanceof DateTime ? $end : date_create($end);
        $start = $start->format("Y-m-d H:i:s");
        $end = $end->format("Y-m-d H:i:s");
//        dump(ST::routeToCaApi("getVksWasInPeriodForTb/" . App::$instance->tbId . "/" . $start . "/" . $end));
//        die;
//        dump($start, $end);
//        die;
        $events =
            Vks::where('start_date_time', ">=", $start)
                ->where('start_date_time', '<=', $end)
                ->whereIn('status', [VKS_STATUS_APPROVED])
                ->orderBy('start_date_time')
                ->with('connection_codes')
                ->get(['id', 'start_date_time', 'end_date_time', 'title', 'status', 'approved_by', 'owner_id', 'is_simple', 'flag']);

        //pull from central server
        $getFromCA = Curl::get(ST::routeToCaApi("getVksWasInPeriodForTb/" . App::$instance->tbId . "/" . $start . "/" . $end));

        $getFromCA = json_decode($getFromCA);
//            dump($getFromCA->data);
        if ($getFromCA && $getFromCA->status == 200) {
            $getFromCA = $getFromCA->data;
        } else {
            $getFromCA = array();
        }
        //add to events container
//            dump($getFromCA);
        foreach ($getFromCA as $CAVks) {
            $CAVks->fromCa = true;
            $events[] = $CAVks;
        }


        //!get event
        //event customization
        foreach ($events as $event) {
            $start = date_create($event->start_date_time);
            $end = date_create($event->end_date_time);
            $event->start_time = $start->format("H:i");
            $event->end_time = $end->format("H:i");
            $event->date = $start->format("d.m.Y");
            $event->start = $start->format("Y-m-d H:i");
            $event->end = $end->format("Y-m-d H:i");
            $event->titleCustom = $event->title;
            $event->title = null; //костыль
            ST::deployColorScheme($event, App::$instance->user->colors['local_default']);

            if (Auth::isAdmin(App::$instance)) {
                if ($event->status == VKS_STATUS_PENDING) {
                    $event->titleCustom = '<span class="label label-warning">согл.</span> ' . $event->titleCustom;
                    ST::deployColorScheme($event, App::$instance->user->colors['local_pending']);
                }
            }
            if (isset($event->fromCa)) {
                $event->titleCustom = '<span class="label label-warning">ЦА</span> ' . $event->titleCustom;
                ST::deployColorScheme($event, App::$instance->user->colors['fromca_no_local_linked']);
            }

            if (!isset($event->fromCa) && $event->is_simple) {
                ST::deployColorScheme($event, App::$instance->user->colors['local_simple']);
            }

            if (Auth::isAdmin(App::$instance)) {
                if ($event->is_verified_by_user == USER_VERIFICATION_MAIL_SENDED) {
                    $event->titleCustom = '<span class="label label-default">?</span> ' . $event->titleCustom;

                } else if ($event->is_verified_by_user == USER_VERIFICATION_APPROVED) {
                    $event->titleCustom = '<span class="label label-info">Ок</span> ' . $event->titleCustom;
                } else if ($event->is_verified_by_user == USER_VERIFICATION_DECLINED) {
                    $event->titleCustom = '<span class="label label-danger">Отказ</span> ' . $event->titleCustom;
                }
            }

            if (Auth::isAdmin(App::$instance)) {
                if ($event->admin_id == App::$instance->user->id) {
                    ST::deployColorScheme($event, App::$instance->user->colors['local_im_admin']);
                }
            }

            if (Auth::isLogged(App::$instance) && !(Auth::isAdmin(App::$instance))) {
                if ($event->owner_id == App::$instance->user->id && $event->status != VKS_STATUS_PENDING) {
                    $event->backgroundColor = "#E65C00";
                    ST::deployColorScheme($event, App::$instance->user->colors['local_im_owner']);
                }
            }

        } //end foreach
        //!event customization

        return $events; //output

    }

    public function feedAtParticipant()
    {

        $start = date_create($this->request->query->get('start'))->setTime(0, 0);
        $end = date_create($this->request->query->get('end'))->setTime(23, 59);
        $requested_participant_id = intval($this->request->query->get('requested_participant_id'));

        $vkses = Vks::where('start_date_time', ">=", $start)
            ->where('start_date_time', '<=', $end)
            ->whereIn('status', [VKS_STATUS_PENDING, VKS_STATUS_APPROVED])
            ->notSimple()
            ->with('participants')
            ->get();
        $filtered_vkses = array();
        if (count($vkses))
            foreach ($vkses as $vks) {
                if (count($vks->participants)) {
                    foreach ($vks->participants as $participant) {
                        if ($participant->id === $requested_participant_id)
                            $filtered_vkses[] = $vks;
                    }
                }
            }
//        dump($vkses);

        $filtered_vkses = $this->prepareEvents($filtered_vkses);

        print json_encode($filtered_vkses); //output

    }

    public function prepareEvents($events)
    {
        if (count($events))
            foreach ($events as $event) {
                $start = date_create($event->start_date_time);
                $end = date_create($event->end_date_time);
//
                if (isset($event->fromCa)) {
                    $start = date_create($event->start_date_time, new DateTimeZone(App::$instance->opt->ca_timezone));
                    $end = date_create($event->end_date_time, new DateTimeZone(App::$instance->opt->ca_timezone));
                    $mskStart = clone($start);
                    $mskEnd = clone($end);
                    $start->setTimezone(new DateTimeZone(App::$instance->opt->timezone));
                    $end->setTimezone(new DateTimeZone(App::$instance->opt->timezone));

                    $event->mks_start_time = $mskStart->format("H:i");
                    $event->mks_end_time = $mskEnd->format("H:i");
                    $event->mks_date = $mskStart->format("d.m.Y");
                    $event->mks_start = $mskStart->format("Y-m-d H:i");
                    $event->mks_end = $mskEnd->format("Y-m-d H:i");

                }
                $event->start_time = $start->format("H:i");
                $event->end_time = $end->format("H:i");
                $event->date = $start->format("d.m.Y");
                $event->start = $start->format("Y-m-d H:i");
                $event->end = $end->format("Y-m-d H:i");
                ST::deployColorScheme($event, App::$instance->user->colors['local_default']);

                if (!isset($event->fromCa)) {
                    $event->titleCustom = "<span class='label label-success label-as-badge'>#" . $event->id . "</span> ";
                } else {
                    $event->titleCustom = "<span class='label label-warning label-as-badge'>#" . $event->id . "</span> ";
                }

                if ($event->status == VKS_STATUS_PENDING) {
                    $event->titleCustom = $event->titleCustom . '<span class="label label-info">Pending</span> ';
                    ST::deployColorScheme($event, App::$instance->user->colors['local_pending']);
                }

                if (isset($event->fromCa)) {

                    $event->titleCustom = $event->titleCustom . '<span class="label label-info label-as-badge" style="background-color: brown;">СA</span> ';

                    ST::deployColorScheme($event, App::$instance->user->colors['fromca_local_linked']);

                    if (!$event->isLinked) {
                        if (!$event->tbFlag) {
                            ST::deployColorScheme($event, App::$instance->user->colors['fromca_no_local_linked']);
                        }
                        $event->titleCustom = $event->titleCustom . '<span class="label label-info" style="background-color: #F2EE0F; color: #000;">НЗ</span> ';
                    }

                    if ($event->flag) {
                        ST::deployColorScheme($event, App::$instance->user->colors['fromca_with_flag']);
                    }

                    if ($event->tbFlag) {
                        ST::deployColorScheme($event, App::$instance->user->colors['local_with_flag']);
                    }
                }

                if (Auth::isAdmin(App::$instance) && isset($event->link_ca_vks_id) && !$event->other_tb_required) {
                    $event->titleCustom = $event->titleCustom . '<span class="label label-info">TbToCa</span>';
//                $event->backgroundColor = "#B9BAB2";
//                $event->borderColor = "#B9BAB2";
                }

                if (!isset($event->fromCa) && $event->other_tb_required) {
                    $event->titleCustom = $event->titleCustom . '<span class="label label-info">TbToTb</span> ';
                }

                if (!isset($event->fromCa) && $event->is_simple) {
                    $event->titleCustom = $event->titleCustom . '<span class="label label-info">Simple</span> ';
                    ST::deployColorScheme($event, App::$instance->user->colors['local_simple']);
                }

                if (Auth::isAdmin(App::$instance)) {
                    if (!isset($event->fromCa) && $event->record_required) {
                        $event->titleCustom = $event->titleCustom . "<span class='label label-danger'><span class='glyphicon glyphicon-facetime-video'></span></span> ";
                    }

                    if (!isset($event->fromCa)) {
                        if ($event->flag) {
                            ST::deployColorScheme($event, App::$instance->user->colors['local_with_flag']);
//                    $event->titleCustom = $event->titleCustom.'<span class="label label-danger" style="background-color: #F730D5">Flag</span> ';
                        }
                    } else {

                    }
                }

                if (Auth::isAdmin(App::$instance)) {
                    if ($event->admin_id == App::$instance->user->id) {
                        ST::deployColorScheme($event, App::$instance->user->colors['local_im_admin']);
                    }
                }

                if (Auth::isLogged(App::$instance) && !(Auth::isAdmin(App::$instance))) {
                    if ($event->owner_id == App::$instance->user->id && $event->status != VKS_STATUS_PENDING) {
                        ST::deployColorScheme($event, App::$instance->user->colors['local_im_owner']);
                    }
                }

                $event->titleCustom .= $event->start_time . " - " . $event->end_time;

//            if (isset($event->fromCa)) {
//                $event->titleCustom .= "<div class='plank-title'>мск(" .$event->mks_start_time . " - " . $event->mks_end_time . ")</div>";
//            }
                $event->titleCustom .= "<div class='plank-title'>" . $event->title . "</div>";
                $event->title = null; //костыль
            }
        return $events;
    }
}