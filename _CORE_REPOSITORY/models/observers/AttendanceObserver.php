<?php
class AttendanceObserver
{

    public function __construct(){
//        dump('observer on');
    }

    public function creating($model)
    {
//        dump('creating');
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

        App::$instance->cache->renewTag("tag.att.".App::$instance->tbId.".fullpath.{$model->id}");
        App::$instance->cache->renewTag("tag.att.".App::$instance->tbId.".parentInfo.{$model->id}");
        App::$instance->cache->renewTag("tag.att.".App::$instance->tbId.".tree");

//        dump('saved');
    }

    public function deleted($model)
    {
//        dump('deleted');
    }

}