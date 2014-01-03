<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 22.11.13
 * Time: 18:19
 */

namespace Ron\RaspberryPiBundle\Lib;


class EbayKleinanzeige
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var string
     */
    protected $locationStr;
    /**
     * @var string
     */
    protected $link;

    /**
     * @var $string
     */
    protected $date;

    public function __construct($id = null)
    {
        if (null != $id) {
            $this->setId($id);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() . '|' . $this->getDescription()
        . '|' . $this->getPrice() . '|' . $this->getLocationStr();
    }


    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        if(null !== $pos = strpos($date, 'Heute')){
            $date = substr($date, $pos + 6);
        }
        $this->date = new \DateTime($date);
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $location
     */
    public function setLocationStr($location)
    {
        $this->locationStr = $location;
    }

    /**
     * @return string
     */
    public function getLocationStr()
    {
        return $this->locationStr;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


} 