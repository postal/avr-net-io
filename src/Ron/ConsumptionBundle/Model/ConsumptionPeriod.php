<?php
/**
 * @author Ron
 */

namespace Ron\ConsumptionBundle\Model;


/**
 * Class ConsumptionPeriod
 * @package Ron\ConsumptionBundle\Model
 */
/**
 * Class ConsumptionPeriod
 * @package Ron\ConsumptionBundle\Model
 */
class ConsumptionPeriod
{

    /**
     *
     */
    const CONSUMPTION_TYPE_ENERGY = 'energy';
    /**
     *
     */
    const CONSUMPTION_TYPE_GAS = 'gas';
    /**
     *
     */
    const CONSUMPTION_TYPE_WATER = 'water';

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
     * @var
     */
    protected $gas;
    /**
     * @var
     */
    protected $water;

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

    /**
     * @param $type
     * @param $value
     */
    public function setConsumption($type, $value)
    {
        if ($this->isValidType($type)) {
            $name = 'set' . ucfirst($type);
            $this->$name($value);
        }
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return array(
            self::CONSUMPTION_TYPE_ENERGY,
            self::CONSUMPTION_TYPE_GAS,
            self::CONSUMPTION_TYPE_WATER,
        );
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isValidType($type)
    {
        return in_array($type, $this->getTypes());
    }
}