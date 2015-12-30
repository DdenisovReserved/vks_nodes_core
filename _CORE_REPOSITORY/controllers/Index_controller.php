<?php

class Index_controller extends Controller
{
    public function index()
    {
        $sc = new Settings_controller();
        $pm = $sc->getPublicMessage();
        $this->render("vks/calendar", compact('pm'));
    }


    public function settings()
    {
        $this->render("settings/index");
    }


    public function changelog()
    {
        $this->render("misc/changelog");
    }
}