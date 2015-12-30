<?php
class Router {
    private $sect;
    private $filename;

    function __construct () {
        if (isset($_GET['r'])) {
            //разбить запрос
            $splitReq = explode('/',$_GET['r']);
            //получить имя файла, достать последний массив из конца сстроки запроса
            $this->filename = array_pop($splitReq);
            //все остальное в массиве, это путь к файлу
            $this->sect = $splitReq;
            //process request
            $this->processRequest();
        } else {
            if (isset($_GET['route'])) {
                $frontController = new FrontController();
                $frontController->run();
                exit;
            }
            ST::redirectToRoute("Index/index");
        }

    }

    function processRequest() {
//        ST::makeDebug($this->sect.DIRECTORY_SEPARATOR.$this->filename.".php");
        //var for path
        $path = '';
        //make path
        foreach ($this->sect as $pathsect){
            $path .= $pathsect.DIRECTORY_SEPARATOR;
        }
        //check if file exist
        if (file_exists($path.$this->filename.".php")) {
            require($path.$this->filename.".php");
        } else {
            die(ExceptionHandler::showEmptyMessage('Данная страница не существует'));
        }
    }
    static function getPathList() {
        return isset($_REQUEST["r"]) ? explode("/",$_REQUEST["r"]) : null;
    }
    static function getCurrentPage() {
        $path = self::getPathList();
        return !is_null( $path ) ? strtolower(array_pop($path)) : null;
    }
    static function buildBreadcramps() {
        $path = self::getPathList();

        //тут должна быть локализцация
        return !is_null( $path ) ? implode(" -> ", $path) : "";
    }

}