<?php

class Dashboard_controller extends Controller
{
    public function index()
    {
        //take only today
        Auth::isAdminOrDie(App::$instance);
        $start = date_create()->setTime(0, 0);
        $end = date_create()->setTime(23, 59);
        $now = date_create();
        $calFeeder = new CalendarFeed_controller();
        $events = $calFeeder->feedInPeriod($start, $end);
        $sortedEvents = [];

        foreach ($events as $event) {
            if ($now < $event->start_date_time) {
                $sortedEvents['future'][] = $event;
            } elseif ($now > $event->end_date_time) {
//                dump($now, date_create($event->end_date_time));
                $sortedEvents['past'][] = $event;
            } elseif ($now > $event->start_date_time && $now < $event->end_date_time) {
                $sortedEvents['now'][] = $event;
            }
        }

        if (isset($sortedEvents['future']))
            $sortedEvents['future']['html'] = App::$instance->twig->render('dashboards/event.twig', array(
                'sortedEvents' => $sortedEvents['future'],
                'coreHttpAddress' => HTTP_PATH,
                'nodeHttpAddress' => NODE_HTTP_PATH
            ));
        else
            $sortedEvents['future']['html'] = '<i>список пуст</i>';

//        dump($sortedEvents['past']);

        if (isset($sortedEvents['past']))

            $sortedEvents['past']['html'] = App::$instance->twig->render('dashboards/event.twig', array(
                'sortedEvents' => $sortedEvents['past'],
                'coreHttpAddress' => HTTP_PATH,
                'nodeHttpAddress' => NODE_HTTP_PATH
            ));
        else
            $sortedEvents['past']['html'] = '<i>список пуст</i>';

        if (isset($sortedEvents['now']))
            $sortedEvents['now']['html'] = App::$instance->twig->render('dashboards/event.twig', array(
                'sortedEvents' => $sortedEvents['now'],
                'coreHttpAddress' => HTTP_PATH,
                'nodeHttpAddress' => NODE_HTTP_PATH
            ));
        else
            $sortedEvents['now']['html'] = '<i>список пуст</i>';
//        dump($sortedEvents);
        $this->render('dashboards/index', compact('sortedEvents'));
    }


    public function apiGetServerTime()
    {
        $now = date_create();
        $currentTime = $now->format('Y-m-d') . "T" . $now->format('H:i:s');
        print json_encode($currentTime);

    }

    public function showSimpleCodes($date)
    {
        $date = date_create($date);
        $sc = new Settings_controller();
        $codes = $sc->getSimpleVksCodeParams();
        $codeRange = range(floatval($codes->code['start']), floatval($codes->code['end']));
        $rangeStringed = [];
        foreach ($codeRange as $code) {
            $rangeStringed[] = (String)$code;
        }
        if (count($rangeStringed)) {
            $vkses = Vks::simple()->where('date', $date)->with(['connection_codes' => function ($query) use ($rangeStringed) {
                $query->whereIn('value', $rangeStringed);
            }])->get();
            $collected = [];
            foreach ($rangeStringed as $code) {
                $collected[$code] = [];
                foreach ($vkses as $vks) {
                    foreach ($vks->connection_codes as $code_vk)
                        $code_vk->value == $code ? $collected[$code][] = $vks : false;
                }
            }
            $date = $date->format('d.m.Y');
            $this->render('Dashboards/showSimpleCodes', compact('collected', 'date'));
        } else {
            die('range is corrupted');
        }

    }

    public function showCACodes($date, $partial=false)
    {

        $pullFromCa = $this->aksForCaTransportVksInDate($date);
        $collected = [];

        $pool = App::$instance->callService("vks_ca_negotiator")->askForPool();


        foreach ($pool as $code) {

            $collected[$code] = [];
        }

        foreach ($collected as $key => $val) {
            foreach ($pullFromCa as $caVks) {
                $caVks->v_room_num == $key ? $collected[$key][] = $caVks : false;
                $caVks->tbVks = Vks::where('link_ca_vks_id', $caVks->id)->first();
            }
        }
//        dump($collected);
        $date = date_create($date)->format('d.m.Y');
//        dump($collected);
        $this->render('Dashboards/showCaPoolCodes', compact('collected', 'date', 'partial'));
    }


    public function aksForCaTransportVksInDate($date) {
        $tmp['tb'] = MY_NODE;
        $tmp['date'] = $date;
        $tmp = http_build_query($tmp);
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $tmp
            )
        ));

        $ask = utf8_decode(file_get_contents(HTTP_PATH . "?route=VksNoSupport/apiGiveBalanceOnTbPool", false, $context));
        if ($ask[0] == "?")
            $ask = substr($ask, 1);
        return json_decode($ask);
    }
}