<?php
class Core {
    function __get($param) {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        } else {
            return null;
        }
    }
    function set($param, $val)  {
        $this->$param = $val;
    }
}