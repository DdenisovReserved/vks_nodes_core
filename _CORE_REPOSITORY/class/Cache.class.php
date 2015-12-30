<?php

class Cache extends Core
{
    public $instance = Null;

    public function __construct()
    {
//        $this->instance = false;
//        return;
        global $params;
        $redis = new Redis();
        //Соединяемся с нашим сервером
//        $connect = false;
        if (!isset(App::$instance->cache) || App::$instance->cache->instance == null ) {
            $connect = $redis->connect($params['memcache']['host'], $params['memcache']['port']);
            $this->instance = $connect ? $redis : Null;
        }


    }

    public function get($key)
    {
        if (!App::$instance->cache->instance) {
            return false;
        }
        $now = date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->getTimestamp();
        $result = $this->getSimple($key);

        if (!$result || !($result instanceof CachedObject)) return false;
        //compare tags
        $tagsCheck = true;
        if (count($result->getTags())) {
            foreach ($result->getTags() as $tagKey => $tagVal) {
                if ($tagStamp = $this->getSimple($tagKey)) {
//                    dump($tagStamp, $tagVal);
                    if ($tagStamp != $tagVal) {
                        $tagsCheck = false;
                    }
                } else {
                    $tagsCheck = false;
                }
            }
        }
        if (!$tagsCheck) return false;
        return $result->getValue();


    }

    public function getSimple($key)
    {
        if (!App::$instance->cache->instance) {
            return false;
        }
        return unserialize($this->instance->get($key));

    }

    public function set($key, CachedObject $object, $expire = 3600) //$expire == seconds
    {
        if (!App::$instance->cache->instance) {
            return false;
        }
        $this->generateTags($object);

        return $this->instance->setex($key, $expire, serialize($object));
    }
    //void
    public function renewTag($tagName)
    {

        $newTagVal = date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->getTimestamp();
        $this->setSimple($tagName, $newTagVal, date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->modify("+7 days")->getTimestamp() - $newTagVal + 1);

//        dump($tagName, $newTagVal);

    }
    // bool
    public function generateTags(CachedObject $object)
    {
        if (count($object->getTags())) {
            $ts = date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->getTimestamp();
            $temporaryTags = array();
            foreach ($object->getTags() as $tag) {
                if ($this->setSimple($tag, $ts, 3600 * 24 * 7)) {
                    $temporaryTags[$tag] = $ts;
                }
            }
            $object->setTags($temporaryTags);
            return true;
        } else {
            return false;
        }
    }

    public function setSimple($key, $val, $lifeTime)
    {
        if (!App::$instance->cache->instance) {
            return false;
        }
//        print($key . " put in cache");

        return $this->instance->setex($key, $lifeTime, serialize($val));
    }

    public function flush($keys)
    {
        if (!App::$instance->cache->instance) {
            return false;
        }
        $this->instance->delete($keys);

    }
}