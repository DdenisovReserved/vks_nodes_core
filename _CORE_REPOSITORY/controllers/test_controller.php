<?php
ini_set('max_execution_time', 10);
ini_set('SMTP', 'smtp.sbrf.ru');

class test_controller extends Controller
{
    function test()
    {
        return $this->render('test/test');
    }

    function test2($date, $attendance_id)
    {
        try {
            $attendance = Attendance::findOrFail($attendance_id);
        } catch (Exception $e) {
            $this->error('404');
        }
        $vc = new Vks_controller();
        $start = date_create($date)->setTime(0, 0);
        $end = date_create($date)->setTime(23, 59);
        $requested_participant_id = intval($attendance_id);

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
                            $filtered_vkses[] = $vc->humanize($vks);
                    }
                }
            }
        return $this->render('test/test2', compact('attendance', 'filtered_vkses', 'date'));

    }

    function timeline() {
        return $this->render('test/timeline');
    }


    function show()
    {
//        $this->render("test/
        $s = ST::microtime_float();
        $start = '2015-11-30';
        $end = '2016-01-11';
        $events =
            Vks::where('start_date_time', ">=", $start)
                ->where('start_date_time', '<=', $end)
                ->where('status', VKS_STATUS_APPROVED)
                ->get();
        $e = ST::microtime_float();
        print($e - $s);
    }

    function react()
    {
        return $this->render('test/react');
    }

    function apiComments()
    {
        $comments = file_get_contents(CORE_REPOSITORY_REAL_PATH . 'files/comments.json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentsDecoded = json_decode($comments, true);
            $commentsDecoded[] = [
                'id' => round(microtime(true) * 1000),
                'author' => $_POST['author'],
                'text' => $_POST['text'],
                'time' => date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format('d.m.Y H:i:s')
            ];

            $comments = json_encode($commentsDecoded, JSON_PRETTY_PRINT);
            file_put_contents(CORE_REPOSITORY_REAL_PATH . 'files/comments.json', $comments);
        }
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');
        echo $comments;
    }

}


