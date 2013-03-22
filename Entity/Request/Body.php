<?php

namespace Qwer\LottoDocumentsBundle\Entity\Request;

use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Currency;
use Qwer\LottoBundle\Entity\Time;

class Body
{

    /**
     *
     * @var \Qwer\LottoBundle\Entity\Client 
     */
    private $client;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\Currency 
     */
    private $currency;

    /**
     *
     * @var int 
     */
    private $externalId;

    /**
     *
     * @var \Qwer\LottoBundle\Entity\Type 
     */
    private $lottoType;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    private $rawBets;
    
    /**
     *
     * @var boolean 
     */
    private $withBonus;
    
    function __construct()
    {
        $this->rawBets = new ArrayCollection();
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 
     * @return \Qwer\LottoDocumentsBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * 
     * @param \Qwer\LottoDocumentsBundle\Entity\Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * 
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * 
     * @param integer $extenalId
     */
    public function setExternalId($extenalId)
    {
        $this->externalId = $extenalId;
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Time
     */
    public function getLottoType()
    {
        return $this->lottoType;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Time $lottoTime
     */
    public function setLottoType(Time $lottoTime)
    {
        $this->lottoType = $lottoTime;
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getRawBets()
    {
        return $this->rawBets;
    }

    /**
     * 
     * @param ArrayCollection $rawBets
     */
    public function setRawBets($rawBets)
    {
        $this->rawBets = $rawBets;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getWithBonus()
    {
        return $this->withBonus;
    }

    /**
     * 
     * @param boolean $withBonus
     */
    public function setWithBonus($withBonus)
    {
        $this->withBonus = $withBonus;
    }

}