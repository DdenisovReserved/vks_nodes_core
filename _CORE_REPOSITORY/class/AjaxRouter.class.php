<?php
class AjaxRouter extends Controller
{
    public $returnValue;

    function construct () {
        parent::constuct();
    }
    function process () {
//        ST::makeDebug("T");
        $getRequest = explode("/",$this->inputData->req);
        $makeCN = $getRequest[0];
//        ST::makeDebug($makeCN);
        $makeAction = $getRequest[1];
        if (class_exists($makeCN))
            $callController = new $makeCN;
        else
            die("bad request");
//        ST::makeDebug($callController."->".$makeAction );
        $this->returnValue = $callController->$makeAction($this->inputData->data);
        return $this;
    }
    function show () {
        print json_encode($this->returnValue);
    }


}