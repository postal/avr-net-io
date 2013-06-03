<?php

namespace Ron\ConsumptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consumption
 */
class Consumption
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $energy;

    /**
     * @var string
     */
    private $water;

    /**
     * @var string
     */
    private $gas;

    /**
     * @var \DateTime
     */
    private $createDate;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set energy
     *
     * @param string $energy
     * @return Consumption
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * Get energy
     *
     * @return string 
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * Set water
     *
     * @param string $water
     * @return Consumption
     */
    public function setWater($water)
    {
        $this->water = $water;

        return $this;
    }

    /**
     * Get water
     *
     * @return string 
     */
    public function getWater()
    {
        return $this->water;
    }

    /**
     * Set gas
     *
     * @param string $gas
     * @return Consumption
     */
    public function setGas($gas)
    {
        $this->gas = $gas;

        return $this;
    }

    /**
     * Get gas
     *
     * @return string 
     */
    public function getGas()
    {
        return $this->gas;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Consumption
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}
