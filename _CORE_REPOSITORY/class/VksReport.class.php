<?php
class VksReport {
    private $request = Null;
    private $object = Null;
    private $result = false;
    private $errors = [];

    /**
     * VksReport constructor.
     * @param null $request
     * @param null $object
     * @param bool $result
     * @param array $errors
     */
    public function __construct($request, $object = Null, $result = false, array $errors = array())
    {
        $this->request = $request;
        $this->object = $object;
        $this->result = $result;
        $this->errors = $errors;
    }


    /**
     * @return null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param null $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param null $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return boolean
     */
    public function isResult()
    {
        return $this->result;
    }

    /**
     * @param boolean $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

}