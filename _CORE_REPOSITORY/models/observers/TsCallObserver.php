<?php
class TsCallObserver
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
        //renew linked vks
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.events.calendar");
        App::$instance->cache->renewTag("tag.".App::$instance->tbId.".vks.controller.api.get.{$model->vks_id}");
    }

    public function deleted($model)
    {
//        dump('deleted');
    }

}