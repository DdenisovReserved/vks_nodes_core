<?php
use Illuminate\Database\Capsule\Manager as Capsule;

class Load_controller extends Controller
{

    public function get($date, $whitchServer = 0)
    {

        if (Auth::isAdmin(App::$instance)) {

            $cacheName = App::$instance->tbId . ".vks.events.calendar_load.{" . date_create($date)->getTimestamp() . "}";
            $result = App::$instance->cache->get($cacheName);
            if (!$result) {
                $settings = new Settings_controller();
                $getServerData = $settings->getServerParam($whitchServer);
                $serverMaxLoad = $getServerData['capacity'];
                $date = date_create($date);
                //prepare return array
                $result = [];
                //fill array with timespans
                //put time to beginning of working day
                $date->setTime(8, 0);
                //define end of the day
                $endOfTheDay = clone($date);
                $endOfTheDay->setTime(20, 0);
                //begin rollin
                $c = 0;
                while ($date <= $endOfTheDay) {
                    $time = clone($date);
                    $date->modify("+15 minutes");
                    $result[$c] = [
                        'time' => $time->format("H:i"),
                        'counter' => 0,
                        'percentage' => 0
                    ];
                    $result[$c]['counter'] = $this->getLoadAtPeriod($date, $time, $whitchServer);
                    $result[$c]['percentage'] = $this->calculateLoad($result[$c]['counter'], $serverMaxLoad);
                    $c++;
                }

                $cachedObj = new CachedObject($result, ['tag.' . $cacheName]);

                App::$instance->cache->set($cacheName, $cachedObj, 3600 * 24 * 3);

            }

            if (ST::isAjaxRequest()) {
                print json_encode($result);
            } else {
                return $result;
            }

        } else {
            if (ST::isAjaxRequest()) {
                print json_encode([]);
            } else {
                return [];
            }

        }
    }

    public function show($date, $whitchServer)
    {
        $imageLink = $this->drawLoadImage($date, $whitchServer);
        $this->render('dashboards/graph1', compact('imageLink'));
    }

    public function showOverAll($date)
    {
        $imageLink = $this->drawOverAllImage($date);
        $this->render('dashboards/graph1', compact('imageLink'));
    }

    public function dataForGraph($date, $whitchServer = 0)
    {
        if (Auth::isAdmin(App::$instance)) {
            $settings = new Settings_controller();
            $getServerData = $settings->getServerParam($whitchServer);
            $serverMaxLoad = $getServerData['capacity'];

            $timeSpot1 = date_create($date);
//        dump($date);
            //prepare return array
            $result = [];
            //fill array with timespans

            //put time to beginning of working day
            $timeSpot1->setTime(8, 0);
            //define end of the day
            $endOfTheDay = clone($timeSpot1);
            $endOfTheDay->setTime(20, 0);

            //begin rollin

            while ($timeSpot1 != $endOfTheDay) {
                $timeSpot2 = clone($timeSpot1);
                $timeSpot1->modify("+15 minutes");
                $loadCounter = $this->getLoadAtPeriod($timeSpot1, $timeSpot2, $whitchServer);
                $resultPhp['times'][] = $timeSpot2->format("H:i");
                $resultPhp['counters'][] = $loadCounter;
                $resultPhp['percents'][] = $this->calculateLoad($loadCounter, $serverMaxLoad);
            }

            return $resultPhp;

        } else {
            return [];
        }
    }

    public function isPassedByCapacity($startTime, $endTime, $attendanceCount, $serverNum)
    {

        $serverCap = $this->getServerCapacity($serverNum);
        $peak = $this->getPeakAtPeriod($startTime, $endTime, $serverNum);
        return $serverCap < ($attendanceCount + $peak) ? false : true;

    }

    public function giveSimpleCode($vksStart, $vksEnd)
    { //pull data from request
        $settings = new Settings_controller();
        $params = $settings->getSimpleVksCodeParams();

        $codeRange = range(floatval($params->code['start']), floatval($params->code['end']));

        $codeGiven = false;

        foreach ($codeRange as $code) {

            $foundedCodes = ConnectionCode::where('value', (String)$code)->with(['vks' => function ($query) use ($vksStart, $vksEnd) {
                $query->where('is_simple', 1)
                    ->where('start_date_time', '<=', date_create($vksEnd)->modify("+" . Settings_controller::getOther('pause_gap') . " minutes"))
                    ->where('end_date_time', '>=', date_create($vksStart)->modify("-" . Settings_controller::getOther('pause_gap') . " minutes"))
                    ->where('status', VKS_STATUS_APPROVED);
            }])->get();

            $isFree = true;

            foreach ($foundedCodes as $codeObj) {
                if ($codeObj->vks) $isFree = false;
            }
            if ($isFree) {
                $codeGiven = $code;
                break;
            }
        }
        return $codeGiven;

    }

    public function showJsLoadGraph($date, $whitchServer = 0)
    {
        $graph = $this->pullLoadDataForJs($date, $whitchServer);
        return $this->render('load/jsGraph', compact('graph'));
    }

    public function pullLoadDataForJs($date, $whitchServer = 0)
    {
        $limit = intval($this->getServerCapacity($whitchServer));
        if (!$limit) $this->error('no-object');
        $getLoad = $this->get($date, $whitchServer);


        $graph = new stdClass();
        $graph->plotTicks = array(); // counter, time
        $graph->data = array();// counter, loadcounter
        $graph->threshold = array(); //counter, limit

        for ($c = 0; $c < count($getLoad); $c++) {
            if (in_array(explode(":", $getLoad[$c]['time'])[1], ['00', '30'])) {
                $graph->plotTicks[] = array($c, $getLoad[$c]['time']);
            } else {
                $graph->plotTicks[] = array($c, '');
            }

            $graph->data[] = array($c, intval($getLoad[$c]['counter']));
            $graph->threshold[] = array($c, $limit);
        }
        return $graph;
    }

    public function drawLoadImage($date, $whitchServer = 0)
    {

        $res = $this->dataForGraph($date, $whitchServer);

        $MyData = new pData();
        $MyData->addPoints($res['percents'], "Load in %");
        $MyData->setAxisName(0, "Participants");
        $MyData->addPoints($res['times'], "Labels");
        $MyData->setSerieDescription("Labels", "Months");
        $MyData->setAbscissa("Labels");

        /* Create the pChart object */
        $myPicture = new pImage(1200, 560, $MyData);

        /* Turn of Antialiasing */
        $myPicture->Antialias = TRUE;

        /* Add a border to the picture */
//$myPicture->drawRectangle(0,0,890,290,array("R"=>0,"G"=>0,"B"=>0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => CORE_REPOSITORY_REAL_PATH . "class/pChart/fonts/Forgotte.ttf", "FontSize" => 11));
        $sn = $whitchServer + 1;
        $myPicture->drawText(200, 35, " Server {$sn} Resource Usage (" . date_create($date)->format("d-m-Y") . ")", array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Set the default font */
        $myPicture->setFontProperties(array("FontName" => CORE_REPOSITORY_REAL_PATH . "class/pChart/fonts/pf_arma_five.ttf", "FontSize" => 8));

        /* Define the chart area */
        $myPicture->setGraphArea(60, 40, 1100, 500);

        $AxisBoundaries = array(0 => array("Min" => 0, "Max" => 100));
        /* Draw the scale */
        $scaleSettings = array("XMargin" => 10, "YMargin" => 10, "Floating" => TRUE, "GridR" => 200, "GridG" => 200, "GridB" => 200, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE, "LabelSkip" => 1, 'Mode' => SCALE_MODE_MANUAL, "ManualScale" => $AxisBoundaries);

        $myPicture->drawScale($scaleSettings);

        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

        /* Draw the stacked area chart */
        $myPicture->drawStackedAreaChart(array("DrawLine" => TRUE, "LineSurrounding" => -20));
        /* Turn on Antialiasing */
        $myPicture->Antialias = TRUE;

        /* Draw the line chart */
        $myPicture->drawLineChart();

        /* Write the chart legend */
        $myPicture->drawLegend(800, 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
//$myPicture->autoOutput(App::$instance->opt->fizPath."class/pChart/pictures/example.drawLineChart.simple.png");
        $myPicture->render(CORE_REPOSITORY_REAL_PATH . "images/graphs/" . App::$instance->tbId . "_load_at_" . date_create($date)->format("y-m-d") . "_on_" . $whitchServer . ".png");

        $path = CORE_REPOSITORY_HTTP_PATH . "images/graphs/" . App::$instance->tbId . "_load_at_" . date_create($date)->format("y-m-d") . "_on_" . $whitchServer . ".png";

        if (ST::isAjaxRequest()) {
            $data = [];
            print json_encode($data['path'] = $path);
        } else {
            return $path;
        }
    }

    private function drawOverAllImage($date)
    {

        $res1 = $this->dataForGraph($date, 0);
        $res2 = $this->dataForGraph($date, 1);

        $MyData = new pData();
        $MyData->addPoints($res1['percents'], "Load in % for server 1");
        $MyData->addPoints($res2['percents'], "Load in % for server 2");
        $MyData->setAxisName(0, "Participants");
        $MyData->addPoints($res1['times'], "Labels");
//        $MyData->addPoints($res2['times'], "Labels");
        $MyData->setSerieDescription("Labels", "Months");
        $MyData->setAbscissa("Labels");


        /* Create the pChart object */
        $myPicture = new pImage(1200, 560, $MyData);

        /* Turn of Antialiasing */
        $myPicture->Antialias = TRUE;

        /* Add a border to the picture */
//$myPicture->drawRectangle(0,0,890,290,array("R"=>0,"G"=>0,"B"=>0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => CORE_REPOSITORY_REAL_PATH . "class/pChart/fonts/Forgotte.ttf", "FontSize" => 11));

        $myPicture->drawText(200, 35, "All Servers Resource Usage (" . $date . ")", array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Set the default font */
        $myPicture->setFontProperties(array("FontName" => CORE_REPOSITORY_REAL_PATH . "class/pChart/fonts/pf_arma_five.ttf", "FontSize" => 8));

        /* Define the chart area */
        $myPicture->setGraphArea(60, 40, 1100, 500);

        $AxisBoundaries = array(0 => array("Min" => 0, "Max" => 100));
        /* Draw the scale */
        $scaleSettings = array("XMargin" => 10, "YMargin" => 10, "Floating" => TRUE, "GridR" => 200, "GridG" => 200, "GridB" => 200, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE, "LabelSkip" => 1, 'Mode' => SCALE_MODE_MANUAL, "ManualScale" => $AxisBoundaries);

        $myPicture->drawScale($scaleSettings);

        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

        /* Draw the stacked area chart */
        $myPicture->drawStackedAreaChart(array("DrawLine" => TRUE, "LineSurrounding" => -20));
        /* Turn on Antialiasing */
        $myPicture->Antialias = TRUE;

        /* Draw the line chart */
        $myPicture->drawLineChart();

        /* Write the chart legend */
        $myPicture->drawLegend(800, 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
//$myPicture->autoOutput(App::$instance->opt->fizPath."class/pChart/pictures/example.drawLineChart.simple.png");
        $myPicture->render(CORE_REPOSITORY_REAL_PATH . "class/pChart/pictures/load_at_" . $date . "_on_overall.png");

        $path = CORE_REPOSITORY_HTTP_PATH . "class/pChart/pictures/load_at_" . $date . "_on_overall.png";
        if (ST::isAjaxRequest()) {
            $data = [];
            print json_encode($data['path'] = $path);
        } else {
            return $path;
        }
    }

    private function getLoadAtPeriod($timeSpot1, $timeSpot2, $serverNum)
    {
        $result = Null;
        switch ($serverNum) {
            case (0):
                foreach (Vks::where('start_date_time', '<=', $timeSpot2)
                             ->where('end_date_time', '>=', $timeSpot1)
                             ->approved()
                             ->get(['id']) as $vks) {

                    $result += Vks_controller::countParticipants($vks->id);
                }
                break;
        }

        return $result;
    }

    private function pullDataAtPeriod($timeSpot1, $timeSpot2, $whitchServer)
    {

        $settings = new Settings_controller();
        $getServerData = $settings->getServerParam($whitchServer);
        $serverMaxLoad = $getServerData['capacity'];

        $timeSpot1 = date_create($timeSpot1);
        //define end of the day
        $endOfTheDay = date_create($timeSpot2);
        if ($timeSpot1 > $endOfTheDay) {
            throw New Exception('start time bigger than end time');
        }
        //begin rollin

        while ($timeSpot1 != $endOfTheDay) {
            $timeSpot2 = clone($timeSpot1);
            $timeSpot1->modify("+15 minutes");
            $loadCounter = $this->getLoadAtPeriod($timeSpot1, $timeSpot2, $whitchServer);
            $resultPhp['times'][] = $timeSpot2->format("H:i");
            $resultPhp['counters'][] = $loadCounter;
            $resultPhp['percents'][] = $this->calculateLoad($loadCounter, $serverMaxLoad);
        }


        return $resultPhp;


    }

    private function getPeakAtPeriod($timeSpot1, $timeSpot2, $serverNum)
    {

        $data = $this->pullDataAtPeriod($timeSpot1, $timeSpot2, $serverNum);

        $peak = max($data['counters']);
        return $peak;

    }

    private function getServerCapacity($serverNum)
    {
        $settings = new Settings_controller();
        return $settings->getServerParam($serverNum)['capacity'];
    }

    private function calculateLoad($current, $max)
    {
        $calc = round(($current * 100) / $max, 1);
        if ($calc) {
            return $calc >= 100 ? 100 : $calc;
        } else {
            return 0;
        }

    }


}