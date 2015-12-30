<?php
//parse config file
class App extends Core {
//        private $params;
    static $instance;
    //singleton

    private function __construct ($params) {
//            ST::makeDebug($params);
        foreach ($params as $params_arr_name=>$params_arr_content ) {
//                    ST::makeDebug($params_arr_name);
            if (is_array($params_arr_content)) {
                $this->set($params_arr_name, (object) $params_arr_content);
//                             foreach ($params_arr_content  as $key=>$val ) {
//                                 $this->$params_arr_name->set($key,$val);
//                             }
            } else {
                $this->set($params_arr_name, $params_arr_content);
            }

        } //foeach end
//            ST::makeDebug($this);
        $this->set("MQ", new MQ());

        return self::$instance = $this;

    }

    static function get_instance($params) {

        if (empty(self::$instance)) {

            self::set_instance($params);
        }
        return self::$instance;
    }

    static function set_instance($params) {
        self::$instance = new self($params);
        return self::$instance;
    }

} //class end