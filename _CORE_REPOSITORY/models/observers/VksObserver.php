<?php
class VksObserver
{

    public function __construct(){
//        print('vks observer on');
    }

    public function created($model)
    {
//        dump('created');
    }

    public function updated($model)
    {
//        dump('updated');
    }

    public function saved($model)
    {
        $date = $model->date instanceof DateTime ? $model->date : date_create($model->date);
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.events.calendar");
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.events.calendar_counters");
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.controller.api.get.{$model->id}");
        App::$instance->cache->renewTag("tag.".App::$instance->tbId . ".vks.events.calendar_load.{" . $date->getTimestamp() . "}");

    }

    public function deleted($model)
    {
//        dump('deleted');
    }

}