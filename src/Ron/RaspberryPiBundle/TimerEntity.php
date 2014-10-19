<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 02.06.14
 * Time: 17:32
 */

namespace Ron\RaspberryPiBundle;


class TimerEntity
{
    protected $name;
    protected $code;
    protected $time;


    public function __construct($name, $code, $time)
    {
        $this->setName($name);
        $this->setCode($code);
        $this->setTime($time);
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

}