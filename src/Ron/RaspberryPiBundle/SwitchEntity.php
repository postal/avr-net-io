<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 02.06.14
 * Time: 17:32
 */

namespace Ron\RaspberryPiBundle;


class SwitchEntity
{
    const STATUS_ON = 1;
    const STATUS_OFF = 0;


    protected $name;
    protected $code;
    protected $status;


    public function __construct($name, $code, $status = self::STATUS_OFF)
    {
        $this->setName($name);
        $this->setCode($code);
        $this->setStatus($status);
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
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = (bool) $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return (bool) $this->status;
    }


} 