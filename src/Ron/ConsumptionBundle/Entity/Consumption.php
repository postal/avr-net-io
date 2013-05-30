<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 30.05.13
 * Time: 21:19
 * To change this template use File | Settings | File Templates.
 */

namespace Ron\ConsumptionBundle\Entity;


class Consumption
{

    protected $gas;
    protected $energy;
    protected $water;

    /**
     * @param mixed $energy
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;
    }

    /**
     * @return mixed
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param mixed $gas
     */
    public function setGas($gas)
    {
        $this->gas = $gas;
    }

    /**
     * @return mixed
     */
    public function getGas()
    {
        return $this->gas;
    }

    /**
     * @param mixed $water
     */
    public function setWater($water)
    {
        $this->water = $water;
    }

    /**
     * @return mixed
     */
    public function getWater()
    {
        return $this->water;
    }
}