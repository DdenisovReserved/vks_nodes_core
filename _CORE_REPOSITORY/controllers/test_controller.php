<?php
ini_set('max_execution_time', 10);
ini_set('SMTP', 'smtp.sbrf.ru');
class test_controller extends Controller
{
    function test()
    {

        return $this->render('test/flot');

    }

    function show() {
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

    function react() {
        return $this->render('test/react');
    }

    function apiComments() {
        $comments = file_get_contents(CORE_REPOSITORY_REAL_PATH.'files/comments.json');
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentsDecoded = json_decode($comments, true);
            $commentsDecoded[] = [
                'id'      => round(microtime(true) * 1000),
                'author'  => $_POST['author'],
                'text'    => $_POST['text'],
                'time'    => date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format('d.m.Y H:i:s')
            ];

            $comments = json_encode($commentsDecoded, JSON_PRETTY_PRINT);
            file_put_contents(CORE_REPOSITORY_REAL_PATH.'files/comments.json', $comments);
        }
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');
        echo $comments;
    }

}


