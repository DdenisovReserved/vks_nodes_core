<?php
use Symfony\Component\HttpFoundation\Response;

class ConnectionCode_controller extends Controller
{
    static function giveCode($date, $byTemplateId)
    {
        $codeTail = '';
        if ($byTemplateId == VKS_TPL_WITH_SUPPORT_BATCHED) {
            foreach ($date as $singleDate) {
                if (!Numerator::where('date', $singleDate)->where("template_id", $byTemplateId)->count()) {
                    Numerator::create(['date' => $singleDate, "template_id" => $byTemplateId, 'counter_value' => 9000]);
                }
            }
        } else {
            if (!Numerator::where('date', $date)->where("template_id", $byTemplateId)->count()) {

                Numerator::create(['date' => $date, "template_id" => $byTemplateId, 'counter_value' => 1]);

            }
        }
        //if this is batched VKS, date must be array of dates
        if ($byTemplateId == VKS_TPL_WITH_SUPPORT_BATCHED) {

            if (!is_array($date)) throw new LogicException('Error when try save batched vks - date given in string format, must be array of dates(Y-m-d format)');

            $currentCounter = Numerator::whereIn('date', $date)
                ->where('template_id', $byTemplateId)
                ->max('counter_value');

        } else {
            $currentCounter = Numerator::where('date', $date)->where("template_id", $byTemplateId)->first(['counter_value'])->counter_value; //current value of numerator
        }

        //find template of code
        $getTpl = CodeTemplate::findOrFail($byTemplateId);

        $castNulls = $getTpl->digit - strlen($currentCounter);

        while ($castNulls != 0) {
            $codeTail .= "0";
            $castNulls--;
        }

        //here your code
        $result = $getTpl->template . $codeTail . $currentCounter;

        if ($byTemplateId == VKS_TPL_WITH_SUPPORT_BATCHED) {
            $newCounter = ++$currentCounter;
            foreach ($date as $singleDate) {
                $numerator = Numerator::where('date', $singleDate)->where("template_id", $byTemplateId)->first();
                $numerator->counter_value = $newCounter;
                $numerator->save();
            }
        } else {
            $numerator = Numerator::where('date', $date)->where("template_id", $byTemplateId)->first();
            $numerator->counter_value = ++$currentCounter;
            $numerator->save();
        }


        return $result;
    }

    public function isCodeInUse($code, $period_start, $period_end, $force_no_ajax = false)
    {

        $use = false;
        $vc = new Vks_controller();
        //how much digits in num
        $digits = strlen((String)$code);
//        dump(Vks::with('connectionCode')
//            ->where('start_date_time', '<=', date_create($period_end)->modify("+".Settings_controller::getOther('pause_gap')." minutes"))
//            ->where('end_date_time', '>=', date_create($period_start)->modify("-".Settings_controller::getOther('pause_gap')." minutes"))
//            ->approved()
//            ->get(['id', 'title', 'start_date_time', 'end_date_time', 'date']));
        foreach (Vks::with('connection_codes')
                     ->where('start_date_time', '<=', date_create($period_end)->modify("+" . Settings_controller::getOther('pause_gap') . " minutes"))
                     ->where('end_date_time', '>=', date_create($period_start)->modify("-" . Settings_controller::getOther('pause_gap') . " minutes"))
                     ->approved()
                     ->get(['id', 'title', 'start_date_time', 'end_date_time', 'date']) as $vks) {


            if (count($vks->connection_codes)) {
                foreach ($vks->connection_codes as $vcode) {

                    if (substr($vcode->value, -$digits) == $code) {
                        $vks = $vc->humanize($vks);
                        $use = $vks;
                        break;
                    }
                }
            }
        }

        if ($force_no_ajax) return $use;

//        ST::makeDebug($use);
        if (ST::isAjaxRequest()) {
            if ($use)
                $use = $use->toJson();

            print json_encode($use);
        } else {
            return $use;
        }

    }

    public static function codesToString($connection_codes)
    {
        $result = '';
        $cc = new Controller();
        if (count($connection_codes)) {
            foreach ($connection_codes as $code) {
                if ($code instanceof ConnectionCode) {

                    if (isset($code->value_raw)) {
                        $result .= "| Код: " . $code->value_raw;
                    } else {
                        $result .= "| Код: " . $code->value;
                    }

                    if (isset($code->tip) && strlen($code->tip)) {
                        $result .= " (" . $code->tip . ") ";
                    }
                } else {
                    $cc->error('500');
                }
            }

        } else {
            $result = '| Код не выдан или не требуется';
        }
        return $result;
    }

    public function apiGetCodesInUse()
    { //post data get

        $start_date = $this->request->request->get("start_date");
        $end_date = $this->request->request->get("end_date");

        if (!$start_date || !$end_date) return print new Response('params has wrong names', Response::HTTP_FAILED_DEPENDENCY);

        try {
            $start = date_create($start_date)->format("Y-m-d H:i");
            $end = date_create($end_date)->format("Y-m-d H:i");
        } catch (Exception $e) {
            return print new Response('params can\'t be converted to dates', Response::HTTP_CONFLICT);
        }
        if (date_create($start_date) > date_create($end_date)) return print new Response('start bigger than end', Response::HTTP_FAILED_DEPENDENCY);

        $sc = new Settings_controller();
        $connCtrl = new ConnectionCode_controller();

        $codesLoadSet = [];

        foreach ($sc->getCodesPostfixSet() as $postfixCode) {
            $vksSearch = $connCtrl->isCodeInUse($postfixCode, $start, $end, true);
            $codesLoadSet[] = array(
                'code' => $postfixCode,
                'usage' => isset($vksSearch->id) ? ST::linkToVksPage($vksSearch->id, true) : false
            );
        }

        print json_encode($codesLoadSet, JSON_PRETTY_PRINT);
    }

}