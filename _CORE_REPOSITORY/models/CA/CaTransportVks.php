<?php

/**
 * Created by PhpStorm.
 * User: temp
 * Date: 12.02.2016
 * Time: 12:14
 */
class CaTransportVks
{
    private $title = '';
    private $participants = array();
    private $date = null;
    private $start_time = null;
    private $end_time = null;
    private $tb = null;
    private $ip = null;
    private $location = null;
    private $v_room_num = null;
    private $ca_participants = null;
    private $owner_id = null;

    /**
     * @return null
     */
    public function getVRoomNum()
    {
        return $this->v_room_num;
    }

    /**
     * @param null $v_room_num
     */
    public function setVRoomNum($v_room_num)
    {
        $this->v_room_num = $v_room_num;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param array $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param null $date
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date->format("d.m.Y");

    }

    /**
     * @return null
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param null $start_time
     */
    public function setStartTime(DateTime $start_time)
    {
        $this->start_time = $start_time->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format("H:i");
    }

    /**
     * @return null
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param null $end_time
     */
    public function setEndTime(DateTime $end_time)
    {
        $this->end_time = $end_time->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->format("H:i");
    }

    /**
     * @return null
     */
    public function getTb()
    {
        return $this->tb;
    }

    /**
     * @param null $tb
     */
    public function setTb($tb)
    {
        $this->tb = $tb;
    }

    /**
     * @return null
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param null $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param null $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return null
     */
    public function getCaParticipants()
    {
        return $this->ca_participants;
    }

    /**
     * @param null $ca_participants
     */
    public function setCaParticipants($ca_participants)
    {
        $this->ca_participants = $ca_participants;
    }

    /**
     * @return null
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param null $owner_id
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }


}