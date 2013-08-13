<?php
/**
 * @author Ron
 */

namespace Ron\ConsumptionBundle\Model;


/**
 * Class ConsumptionPeriod
 * @package Ron\ConsumptionBundle\Model
 */
class ConsumptionPeriod
{

    /**
     * @var
     */
    protected $startDatetime;
    /**
     * @var
     */
    protected $endDatetime;

    /**
     * @var
     */
    protected $energy;

    /**
     * @param mixed $endDatetime
     */
    public function setEndDatetime($endDatetime)
    {
        $this->endDatetime = $endDatetime;
    }

    /**
     * @return mixed
     */
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

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
     * @param mixed $startDatetime
     */
    public function setStartDatetime($startDatetime)
    {
        $this->startDatetime = $startDatetime;
    }

    /**
     * @return mixed
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
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

    /**
     * @var
     */
    protected $gas;
    /**
     * @var
     */
    protected $water;


    /**
     * @param $startDatetime
     * @param $endDatetime
     * @return bool
     */
    public function isInPeriod($startDatetime, $endDatetime)
    {
        if ($endDatetime < $this->getStartDatetime() || $startDatetime > $this->getEndDatetime()) {
            return true;
        }

        return false;
    }
}