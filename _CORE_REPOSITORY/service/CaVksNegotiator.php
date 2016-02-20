<?php

use App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CaVksNegotiator
 */
class CaVksNegotiator
{


    public function transportVksProcessor(Request $request, Vks $vks)
    {

        $ca_pool_codes = App::$instance->callService("vks_ca_negotiator")->askForPool();
//        die(dump($request));
        //if flag no create set return false
        if (!count($ca_pool_codes)) {
            App::$instance->callService('standart_controller')->backWithData('Пул из ЦА не удалось получить');
        }
        if ($request->get('ca_transport_no_create') || !$vks->other_tb_required || $vks->status != VKS_STATUS_APPROVED)
            return new Response('Создание транспортной ВКС не запрошено', Response::HTTP_OK);
        //check if received code
        if (!$request->get('ca_transport_code') || !strlen($request->get('ca_transport_code'))) {
            App::$instance->callService('standart_controller')->backWithData('Запрашивается выдача кода из ЦА, но значение не передано');
        }
        //check if code from pool
        if (!in_array($request->get('ca_transport_code'), $ca_pool_codes)) {
            App::$instance->callService('standart_controller')->backWithData('Запрашиваемый код вне пула ТБ');
        }
        //check if code can be given
        $currentVksStart = $vks->start_date_time;
        $currentVksEnd = $vks->end_date_time;


        $ca_transport_vkses = App::$instance->callService("vks_ca_negotiator")
            ->aksTransportVksInPeriod($currentVksStart, $currentVksEnd, $ca_pool_codes);
        $codesFiltrated = array();
        foreach ($ca_pool_codes as $code) {
            $codesFiltrated[$code] = array();
            if (count($ca_transport_vkses))
                foreach ($ca_transport_vkses as $ca_tr_vks) {
                    if ($code == $ca_tr_vks->v_room_num)
                        $codesFiltrated[$code][] = $ca_tr_vks;
                }
        }

        if (isset($codesFiltrated[$request->get('ca_transport_code')])
            && count($codesFiltrated[$request->get('ca_transport_code')])
        ) {
            return new Response('Запрашиваемый код уже выдан и не может быть использован повторно', Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            //create CA Object and send it
            $transportCaVks = new CaTransportVks();
            $transportCaVks->setTitle($vks->title);
            $transportCaVks->setDate($vks->date);
            $transportCaVks->setStartTime($vks->start_date_time);
            $transportCaVks->setEndTime($vks->end_date_time);
            $transportCaVks->setLocation(MY_NODE);
            $transportCaVks->setCaParticipants($vks->CaInPlaceParticipantCount->participants_count);
            $transportCaVks->setParticipants($vks->compileCaTransportIdsParticipants());
            $transportCaVks->setTb(MY_NODE);
            $transportCaVks->setIp(App::$instance->user->ip);
            $transportCaVks->setVRoomNum($request->get('ca_transport_code'));
            $transportCaVks->setOwnerId(App::$instance->user->id);
            $transportVks = App::$instance->callService("vks_ca_negotiator")->createTransitVksOnCA($transportCaVks);
            if (isset($transportVks->id)) {
                $vks->link_ca_vks_type = VKS_NS;
                $vks->link_ca_vks_id = $transportVks->id;
                $vks->save();
                return new Response("<p>Транспортная ВКС #" . $transportVks->id . " в ЦА, успешно создана</p>", Response::HTTP_OK);

            } else {
//                dump($transportVks);
//                die;
                return new Response("<p>Транспортная ВКС не может быть создана из-за ошибки: " . $transportVks . "</p>", Response::HTTP_INTERNAL_SERVER_ERROR);

            }
        }
    }

    private function createTransitVksOnCA(CaTransportVks $trVks)
    {
        $tmp = [];
        $tmp['title'] = $trVks->getTitle();
        $tmp['participants'] = $trVks->getParticipants();
        $tmp['date'] = $trVks->getDate();
        $tmp['start_time'] = $trVks->getStartTime();
        $tmp['end_time'] = $trVks->getEndTime();
        $tmp['tb'] = MY_NODE;
        $tmp['ip'] = App::$instance->user->ip;
        $tmp['location'] = TB_PATTERN . " банк";
        $tmp['ca_participants'] = $trVks->getCaParticipants();
        $tmp['owner_id'] = App::$instance->user->id;
        $tmp['v_room_num'] = $trVks->getVRoomNum();

        $tmp = http_build_query($tmp);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, HTTP_PATH . "?route=VksNoSupport/apiMakeTransportVks");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $tmp);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
        $ask = curl_exec($curl);
        curl_close($curl);

        if (0 === strpos(bin2hex($ask), 'efbbbf')) {
            $ask = substr($ask, 3);
        }
        $ask = json_decode($ask);
        return $ask;
    }

    /**
     * @param $request
     * @return void
     */
    public function fillRequestWithInputData(Request $request)
    {
        $participants = $request->request->has('participants') ? $request->get('participants') : array();
        $request->request->set('ca_participants_ids', array_merge($participants, [(string)App::$instance->tbId]));

    }

    /**
     * @param Request $request
     * @param \Controller $controller
     * @return void
     */
    public function validateCaParticipants(Request $request, Controller $controller)
    {
        //check if no participants
        if (!intval($request->get('ca_participants'))
            && !count($request->get('ca_participants_ids'))
        ) $controller->backWithData("Вы не выбрали ни других ТБ ни указали кол-во участников в ЦА");
    }


    public function fillRelationEntity(Request $request, Vks $vks)
    {
        //create relation records for transport vks
        //create counter

        $vksToCAInPlaceParticipant = new VksToCAInPlaceParticipant(array(
            'vks_id' => $vks->id,
            'participants_count' => intval($request->get('ca_participants'))
        ));

        $vks->CaInPlaceParticipantCount()->save($vksToCAInPlaceParticipant);

        //create tb ids
        $tb_ids = array();
        //loop all tb participants
        foreach ($request->get('ca_participants_ids') as $tb_id) {
            $tb_ids[] = new VksToCAIdParticipant(array(
                'vks_id' => $vks->id,
                'ca_att_id' => intval($tb_id)
            ));
        }
        $vks->CaIdParticipants()->saveMany($tb_ids);
    }


    public function askForPool()
    {

        $ask = Curl::get(HTTP_PATH . "?route=VksNoSupport/apiGivePool/".MY_NODE);
        return json_decode($ask);
    }

    public function aksTransportVksInPeriod(DateTime $start, DateTime $end, $poolCodes)
    {
        $result = array();
        if (count($poolCodes))
            $result = CAVksNoSupport::where('start_date_time', ">=", $start)
                ->where('start_date_time', '<=', $end)
                ->where('status', VKS_STATUS_TRANSPORT_FOR_TB)
                ->whereIn('v_room_num', $poolCodes)
                ->get();
        return $result;
    }
}

