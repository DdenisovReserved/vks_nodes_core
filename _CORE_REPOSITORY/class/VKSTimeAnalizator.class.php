<?php

class VKSTimeAnalizator
{
    //модели используемые классом
    private $_models = array();

    /**
     * Конструктор
     */
    function VKSTimeAnalizator(DateTime $date)
    {
        //инициализировать оформление и js

        //инициализировать нужные модели
        $this->_models['vks_store'] = new vks_store_model();
        $this->_models['settings'] = new settings_admins_schedule_model();
        //проверить заполнено ли расписание работы админов на эту дату
//        ST::makeDebug(!$this->_models['settings']->checkCoverage($date));

        $this->isDefaultShift = !$this->_models['settings']->checkCoverage($date);
//        if(!$this->_models['settings']->checkCoverage($date)) {
//            echo "null";
//            exit;
// 	    }


    } //constructor end

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return mixed
     * получить ВКС с поддержкой админов в периоде времени
     */
    function findVksWithAdminSupportInPeriod(DateTime $start, DateTime $end)
    {
        global $app;
        $getCou = $this->_models['vks_store']->raw(
            "Select count(id) from {$app->db->schema}vks_store
             where
              (start_date_time<='" . $end->format("m.d.Y H:i") . "'
              and end_date_time>='" . $start->format("m.d.Y H:i") . "')
              and vks_method='1'");
        return $getCou[0][''];
    } // function end

    /**
     * @param DateTime $start_point - только дата
     * @param DateTime $end_point - не обязательный параметр, но может когда-нибудь пригодится
     * @return array
     * получить массив с доступными админами расписаными по времени интервал: 15 минут
     */
    function getArrWithFreeAdminsInTheDay(DateTime $start_point, $isDefaultShift = false, DateTime $end_point = null)
    {
        $xmlSettings = new XmlSettingsManager("config/other-settings.xml");
        $resArray = array();
        //прибавить 8 часов к стартовой, костыль конечно, ну да ладно
        $start_point = date_create(date("d.m.Y H:i", strtotime($start_point->format("d.m.Y H:i") . "+8 hours")));
        //объект нужно склонировать, чтоб не менять оригинал я хз тут косяк какой-то
        $start_pointC = clone $start_point;
        //если конечного объекта не задано, прибаляем 12 часов
        if (!$end_point) {
            $end_pointC = date_create(date("d.m.Y H:i", strtotime($start_pointC->format("d.m.Y H:i") . "+12 hours")));
        } else {
            $end_pointC = clone $end_point;
        }
        /**пока стартовое время меньше или равно конечному:
         * из кол-ва свободных админов вычитать кол-во вкс в поддержкой админов
         * писать
         * это все в массив
         */

        $current_time = date_create('now')->modify("+" . $xmlSettings->getXmlSettings("time-grapth-limit") . " minutes");

        while ($start_pointC <= $end_pointC) {
            if ($start_pointC < $current_time) {
                $resArray[$start_pointC->format("H:i")] = 0;
            } else {

                $resArray[$start_pointC->format("H:i")] =
                    $this->getAvalAdminsInThisPoint($start_pointC, $isDefaultShift) - $this->getVksWithAdminSupportInTimePoint($start_pointC);
            }
            //прибавлять 15 минут к шагу
            date_add($start_pointC, date_interval_create_from_date_string("15 minutes"));
        }
        return $resArray;
    } //function end

    /**
     * @param $point - только время, дата отбрасывается, можно задавать все что угодно
     * @return mixed
     * проверить сколько админов доступно в это время
     */
    function getAvalAdminsInThisPoint(DateTime $point, $isDefaultShift)
    {
        //нужна инициализация вне функции
        return $this->_models['settings']->checkAdminQty($point, $isDefaultShift);;
    } //function end

    /**
     * @param DateTime $point (ДАТА ВРЕМЯ)
     * @return mixed
     * Получить число вкс поддерживаемых администраторами в этой точке времени
     * при анализе учтитывать болванки резерва, !вкс со статусом approve == 3!
     */
    function getVksWithAdminSupportInTimePoint(DateTime $point)
    {

        $getCou = $this->_models['vks_store']->raw("
                SELECT count(id) as cnt
                FROM {$this->_models['vks_store']->tn}
                WHERE
                    (start_date_time<='" . $point->format("Y-m-d H:i") . "'
                    and end_date_time>='" . $point->format("Y-m-d H:i") . "')
                    and status in (1,3,6)");
        return $getCou[0]['cnt'];
    }

    /**
     * создать массив из по данным дефолтной смены
     */
    function makeDefaultShiftData()
    {

        $result = array();

        $defShift = ST::getDefaultAdminShift();

        foreach ($defShift as $shiftData) {
            $makeStart = date_create($shiftData->start_wt);
            $makeEnd = date_create($shiftData->end_wt);
            while ($makeStart <= $makeEnd) {

                $result[$makeStart->format("H:i")] = $shiftData->workers;
                $makeStart->modify("+15 minutes");
            }
        }
        return $result;
    }
    static function isManipulatable ($vksObject) {
        //define current time
        $now = date_create();
        //define vks start time
        $start = date_create($vksObject->start_date_time);
        $end = date_create($vksObject->end_date_time);
        //check if vks in past and then return false, can't manipulate
        if ($now > $end) return false;
        //check for begin is more than 1 hour
        if (( $start->getTimestamp()-$now->getTimestamp() ) >=1800) {
            return true;
        } else {
            return false;
        }

    }
    static function is24ForBegin ($vksObject) {

        return (
            (self::isManipulatable($vksObject)
            && (date_create()->getTimestamp() - date_create($vksObject->start_date_time)->getTimestamp()) <= 86400)
            && $vksObject->is_verified_by_user == USER_VERIFICATION_NONE || $vksObject->is_verified_by_user == USER_VERIFICATION_MAIL_SENDED
        ) ? true : false;

    }
}