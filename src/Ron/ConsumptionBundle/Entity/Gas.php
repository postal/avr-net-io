<?php

namespace Ron\ConsumptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gas
 */
class Gas
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $verbrauch;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * Set verbrauch
     *
     * @param string $verbrauch
     * @return Gas
     */
    public function setVerbrauch($verbrauch)
    {
        $this->verbrauch = $verbrauch;
    
        return $this;
    }

    /**
     * Get verbrauch
     *
     * @return string 
     */
    public function getVerbrauch()
    {
        return $this->verbrauch;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Gas
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
