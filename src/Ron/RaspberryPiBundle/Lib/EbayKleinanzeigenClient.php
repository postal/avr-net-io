<?php

namespace Ron\RaspberryPiBundle\Lib;

/**
 * Class EbayKleinanzeigen
 * @package Ron\RaspberryPiBundle\Lib
 */
class EbayKleinanzeigenClient
{

    /**
     *
     */
    const EBAY_HOST = 'kleinanzeigen.ebay.de';
    /**
     *
     */
    const EBAY_URI = '/anzeigen/s-suchanfrage.html';
    /**
     *
     */
    const SORT_BY_DATE = 'SORTING_DATE';
    /**
     *
     */
    const ACTION_FIND = 'find';

    const PROTOCOL_HTTP = 'http';

    /**
     * @var string
     */
    protected $keywords = '';
    /**
     * @var string
     */
    protected $categoryId = '';
    /**
     * @var string
     */
    protected $locationStr = '';
    /**
     * @var integer
     */
    protected $locationId;
    /**
     * @var integer
     */
    protected $radius;
    /**
     * @var string
     */
    protected $sortingField = self::SORT_BY_DATE;
    /**
     * @var
     */
    protected $adType;
    /**
     * @var
     */
    protected $posterType;
    /**
     * @var int
     */
    protected $pageNum = 1;
    /**
     * @var string
     */
    protected $action = self::ACTION_FIND;
    /**
     * @var float
     */
    protected $maxPrice;
    /**
     * @var float
     */
    protected $minPrice;

    protected $protocol = self::PROTOCOL_HTTP;

    /**
     * @param mixed $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param mixed $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $adType
     * @return $this
     */
    public function setAdType($adType)
    {
        $this->adType = $adType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdType()
    {
        return $this->adType;
    }

    /**
     * @param string $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param string $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $location
     * @return $this
     */
    public function setLocationStr($location)
    {
        $this->locationStr = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocationStr()
    {
        return $this->locationStr;
    }

    /**
     * @param mixed $locationId
     * @return $this
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @param mixed $maxPrice
     * @return $this
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param mixed $minPrice
     * @return $this
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * @param int $pageNum
     * @return $this
     */
    public function setPageNum($pageNum)
    {
        $this->pageNum = $pageNum;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageNum()
    {
        return $this->pageNum;
    }

    /**
     * @param mixed $posterType
     * @return $this
     */
    public function setPosterType($posterType)
    {
        $this->posterType = $posterType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosterType()
    {
        return $this->posterType;
    }

    /**
     * @param mixed $radius
     * @return $this
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param string $sortingField
     * @return $this
     */
    public function setSortingField($sortingField)
    {
        $this->sortingField = $sortingField;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortingField()
    {
        return $this->sortingField;
    }

    /**
     * Returns the url to search on ebay
     *
     * @return string
     */
    public function getUrl()
    {
        $url = $params = '';
        $url .= $this->getProtocol() . '://' . self::EBAY_HOST . self::EBAY_URI . '?';
        foreach ($this->getParams() as $name => $value) {
            $params .= '&' . $name . '=' . urlencode($value);
        }

        return $url . substr($params, 1);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = array(
            'locationId' => $this->getLocationId(),
            'locationStr' => $this->getLocationStr(),
            'sortField' => $this->getSortingField(),
            'radius' => $this->getRadius(),
            'posterType' => $this->getPosterType(),
            'minPrice' => $this->getMinPrice(),
            'maxPrice' => $this->getMaxPrice(),
            'pageNum' => $this->getPageNum(),
            'keywords' => $this->getKeywords(),
            'categoryId' => $this->getCategoryId(),
            'adType' => $this->getAdType(),
            'action' => $this->getAction(),
        );

        return $params;
    }

} 