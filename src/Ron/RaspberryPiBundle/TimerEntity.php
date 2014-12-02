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
    protected $groupCode;
    protected $code;
    protected $times;
    protected $timeUnit;


    public function __construct($name, $groupCode, $code, $time, $timeUnit = 'minutes')
    {
        $this->setName($name);
        $this->setCode($code);
        $this->setTimes($time);
        $this->setTimeUnit($timeUnit);
        $this->groupCode = $groupCode;
    }

    /**
     * @param mixed $timeUnit
     */
    public function setTimeUnit($timeUnit)
    {
        $this->timeUnit = $timeUnit;
    }

    /**
     * @return mixed
     */
    public function getTimeUnit()
    {
        return $this->timeUnit;
    }

    /**
     * @param mixed $time
     */
    public function setTimes($time)
    {
        $this->times = $time;
    }

    /**
     * @return mixed
     */
    public function getTimes()
    {
        return $this->times;
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

    /**
     * @param mixed $groupCode
     */
    public function setGroupCode($groupCode)
    {
        $this->groupCode = $groupCode;
    }

    /**
     * @return mixed
     */
    public function getGroupCode()
    {
        return $this->groupCode;
    }

}