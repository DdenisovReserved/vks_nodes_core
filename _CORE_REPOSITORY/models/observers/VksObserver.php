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

        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.events.calendar");
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.controller.api.get.{$model->id}");
//        dump('saved');

//        die;
    }

    public function deleted($model)
    {
//        dump('deleted');
    }

}