<?php
use Symfony\Component\HttpFoundation\Response;

ini_set('max_execution_time', 10);
ini_set('SMTP', 'smtp.sbrf.ru');

class test_controller extends Controller
{
    function test()
    {

//        die;
//        header("HTTP/1.1 401 Unauthorized");
//        header("HTTP/1.1 500 Internal Server Error");

        return new Response("test", Response::HTTP_INTERNAL_SERVER_ERROR);

//        $vks = Vks::find(92);
////        $vks->date = '16.02.2016';
////        $vks->start_date_time = '16.02.2016 11:15';
////        $vks->end_date_time = '16.02.2016 12:45';
//        dump($vks->date);
//        dump($vks->start_date_time);
//        dump($vks->end_date_time);

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

    function timeline()
    {
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

    function t5() {
        $this->solution('кот','ксот');
    }


    function solution($aString, $bString)
    {

        if (!strlen($aString) || !strlen($bString)) {
            return false;
        }
        //make collections
        $aCharCollection = preg_split('//u',$aString,-1,PREG_SPLIT_NO_EMPTY);
        $bCharCollection = preg_split('//u',$bString,-1,PREG_SPLIT_NO_EMPTY);
        //count
        $aCharCount = count($aCharCollection);
        $bCharCount = count($bCharCollection);


        //check utmost variants
        if ($aCharCollection === $bCharCollection) {
            dump("equals");
            return 'РАВЕНСТВО';
        }

        if (abs($aCharCount - $bCharCount) > 1) {
            dump("unreachable");
            return 'НЕВОЗМОЖНО';
        }

        if ($aCharCount < $bCharCount) { //insert required
            dump($this->insertAssertion($aCharCollection, $bCharCollection));
        } else if ($aCharCount > $bCharCount) { //delete required
            dump($this->deleteAssertion($aCharCollection, $bCharCollection));
        } else { //switch required
            dump($this->switchAssertion($aCharCollection, $bCharCollection));
        }
    }

    function insertAssertion(array $input, array $target) {

        for ($i = 0; $i <= count($input); $i++) {
            foreach (range(chr(0xE0),chr(0xFF)) as $ruChar) {
                $compiledChars = array();
                $ruChar = iconv('CP1251','UTF-8',$ruChar);
                $compiledChars[$i] = $ruChar;
                foreach($input as $k=>$val) {
                    if ($k >= $i) {
                        $compiledChars[$k+1] = $val;
                    } else {
                        $compiledChars[$k] = $val;
                    }
                }
                ksort($compiledChars);

                if ($compiledChars === $target) {
                    return 'ВСТАВИТЬ ' . $ruChar;
                }
            }
        }
        return false;
    }

    function deleteAssertion(array $input, array $target) {

        for ($i = 0; $i < count($input); $i++) {
                $compiledChars = $input;
                $delChar = $compiledChars[$i];
                unset($compiledChars[$i]);
                if (implode($compiledChars) === implode($target)) {
                    return 'УДАЛИТЬ ' . $delChar;
                }
            }
        return false;
    }

    function switchAssertion(array $input, array $target) {
       //count chars
        $inpCharsCount = array_count_values($input);
        $targetCharsCount = array_count_values($target);
        ksort($inpCharsCount);
        ksort($targetCharsCount);
        if ($inpCharsCount === $targetCharsCount) {
            //begin switches
            for($i = 0; $i < count($input)-1; $i++) {
                $compiledChars = $input;
                $t = $compiledChars[$i];
                $compiledChars[$i] = $compiledChars[$i+1];
                $compiledChars[$i+1] = $t;
                if (implode($compiledChars) === implode($target)) {
                    return "ПОМЕНЯТЬ ".$t ." ".$compiledChars[$i];
                }
            }
        }
    }

}


