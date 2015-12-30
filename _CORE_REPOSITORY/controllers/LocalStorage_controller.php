<?php

class LocalStorage_controller extends Controller
{
    const SAVE_PATH = NODE_REAL_PATH . "storage/temporary/";

    //ajax only, data from post
    public function set()
    {
//        dump()
        if (!$this->request->request->has('key')
            || !$this->request->request->has('value')
        ) {
            print json_encode('error: required parameters is empty!');
            die;
        }
        if (ST::isAjaxRequest()) {
            try {

                file_put_contents(self::SAVE_PATH . App::$instance->user->id. "_" . $this->request->request->get('key'), $this->request->request->get('value'));
                $result = true;
            } catch (Exception $e) {
                $result = false;
            }
            print json_encode($result);
        }
    }

    public function setValue($key, array $value)
    {
        if (!strlen($key)
            || empty($value)
        ) {
            Throw new InvalidArgumentException('required params is empty');
        }

        $value = json_encode($value, JSON_FORCE_OBJECT);

        try {
            file_put_contents(self::SAVE_PATH . App::$instance->user->id. "_" . $key, $value);
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        return $result;

    }

    public function get($key, $ajax = true)
    {

        if (file_exists(self::SAVE_PATH . App::$instance->user->id. "_" . $key)) {
            try {
                $result = file_get_contents(self::SAVE_PATH . App::$instance->user->id . "_" . $key);
            } catch (Exception $e) {
                $result = [];
            }

        } else {
            $result = [];
        }

        if (!$ajax) {
            return (array) json_decode($result);
        } else {
            print $result;
        }

    }

    public function isExist($key) {

        $result = false;
        if (file_exists(self::SAVE_PATH . App::$instance->user->id. "_" . $key)) {
            $result = true;
        }
        if (!ST::isAjaxRequest()) {
            return $result;
        } else {
            print json_encode($result);
        }
    }

    public function remove($key)
    {
        if (file_exists(self::SAVE_PATH . App::$instance->user->id. "_" . $key)) {
            unlink(self::SAVE_PATH . App::$instance->user->id. "_" .$key);
        }
    }

    static public function staticRemove($key)
    {
        $s = new Self();
        $s->remove($key);
    }

}

