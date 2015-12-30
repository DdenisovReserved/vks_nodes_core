<?php

//define order/limit/offset
trait sorterTrait
{

    function getRawOrder($defaultOrder = 'id')
    {
        return @$_REQUEST['order'] ? $_REQUEST['order'] : $defaultOrder;
    }

    function getQOrder($defaultOrder = 'id')
    {
        $order = explode(" ", $this->getRawOrder());
        return @$_REQUEST['order'] ? $order[0] : $defaultOrder;
    }

    function getQVector($defaultVector = 'desc')
    {

        $order = explode(" ", $this->getRawOrder());

        return count($order) > 1 ? $order[1] : $defaultVector;
    }

    function getQlimit($defaultLimit = 10)
    {
        return @$_REQUEST['limit'] ? $_REQUEST['limit'] : $defaultLimit;
    }

    function getQOffset($defaultOffset = 0)
    {
        return @$_REQUEST['offset'] ? $_REQUEST['offset'] : $defaultOffset;
    }

    public static function parseOrderInput($inputString)
    {
        //init result string
        $result = [];
        //explode string, need get field for order, and second is desc or as order
        $orderParams = explode(" ", $inputString);
        //if arguments more than 2
        if (count($orderParams) > 2) throw new Exception('To many arguments');
        //check length of field init(its zero element in array)
        if (strlen($orderParams[0]) > 15) throw new Exception('field init is too long');
        //check order init, it's must be in ASC or DESC
        if (!in_array(strtolower($orderParams[1]), ['asc', 'desc'])) throw new Exception('order init is invalid');

        //if all tests passed return array
        $result[$orderParams[0]] = $orderParams[1];

        return $result;
    }

}
