<?php
use Symfony\Component\Debug\Exception\ClassNotFoundException;

class Core
{
    private $services = array();

    function __get($param)
    {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        } else {
            return null;
        }
    }

    function set($param, $val)
    {
        $this->$param = $val;
    }

    function registerService($alias, $className)
    {
        $this->services[$alias] = $className;
    }

    function callService($alias, array $params = array(), $singleton = true)
    {
        if ($singleton) {
            if (isset($this->$alias)) {
                return $this->$alias;
            } else {
                if (array_key_exists($alias, $this->services)) {
                    $this->$alias = new $this->services[$alias](implode(", ", $params));
                    return $this->$alias;
                } else {
                    throw new Exception('Service with alias ' . $alias . " not found");
                }
            }
        } else {
            $this->$alias = new $this->services[$alias](implode(", ", $params));
        }

    }
}