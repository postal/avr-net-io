<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 27.12.14
 * Time: 22:39
 */

namespace Ron\RaspberryPiBundle\Lib;


/**
 * Class Sun
 * @package Ron\RaspberryPiBundle\Lib
 */
/**
 * Class Sun
 * @package Ron\RaspberryPiBundle\Lib
 */
class Sun
{
    /**
     * @var float
     */
    protected $latitude;
    /**
     * @var float
     */
    protected $longitude;

    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


    /**
     * @param string $dateString
     * @return \DateTime
     */
    public function getDate($dateString = 'now')
    {
        return new \DateTime($dateString);
    }

    /**
     * @param null $date
     * @return \DateTime
     */
    public function getSunrise($date = null)
    {
        if (!$date instanceof \DateTime) {
            $date = $this->getDate();
        }

        $sunriseTimestamp = date_sunrise(
            $date->format('U'),
            SUNFUNCS_RET_TIMESTAMP,
            $this->getLatitude(),
            $this->getLongitude()
        );

        return \DateTime::createFromFormat('U', $sunriseTimestamp);
    }


    /**
     * @return \DateTime
     */
    public function getNextSunrise()
    {
        $sunrise = $this->getSunrise();
        if ($sunrise->format('U') < $this->getDate()->format('U')) {
            $sunrise = $this->getSunrise($this->getDate('+1 day'));
        }

        return $sunrise;
    }


    /**
     * @param null $date
     * @return \DateTime
     */
    public function getSunset($date = null)
    {
        if (!$date instanceof \DateTime) {
            $date = $this->getDate();
        }

        $sunsetTimestamp = date_sunset(
            $date->format('U'),
            SUNFUNCS_RET_TIMESTAMP,
            $this->getLatitude(),
            $this->getLongitude()
        );

        return \DateTime::createFromFormat('U', $sunsetTimestamp);
    }


    /**
     * @return \DateTime
     */
    public function getNextSunset()
    {
        $sunset = $this->getSunset();
        if ($sunset->format('U') < $this->getDate()->format('U')) {
            $sunset = $this->getSunset($this->getDate('+1 day'));
        }

        return $sunset;
    }
}