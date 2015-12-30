<?php
class CachedObject
{

    private $value = Null;
    private $tags = Null;
    private $expire = Null; //timestamp!

    /**
     * CachedObject constructor.
     * @param null $value
     * @param null $tags
     */
    public function __construct($value, $tags)
    {
        $this->value = $value;
        $this->tags = $tags;
        $this->expire = date_create()->setTimezone(new DateTimeZone(App::$instance->opt->ca_timezone))->modify("+7 days")->getTimestamp();
    }

    /**
     * @return null
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param null $expire
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param null $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }



}