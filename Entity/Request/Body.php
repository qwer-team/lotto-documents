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
     * @var \Qwer\LottoBundle\Entity\Type 
     */
    private $lottoTime;

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
    
    /**
     *
     * @var integer 
     */
    private $drawNum;
    
    /**
     *
     * @var string 
     */
    private  $token;
    
    /**
     *
     * @var string 
     */
    private  $tokenStr;

    function __construct()
    {
        $this->rawBets = new ArrayCollection();
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Time
     */
    public function getLottoTime()
    {
        return $this->lottoTime;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Time $lottoTime
     */
    public function setLottoTime(Time $lottoTime)
    {
        $this->lottoTime = $lottoTime;
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

    public function getDrawNum()
    {
        return $this->drawNum;
    }

    public function setDrawNum($drawNum)
    {
        $this->drawNum = $drawNum;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
    
    public function getTokenStr()
    {
        return $this->tokenStr;
    }

    public function setTokenStr($tokenStr)
    {
        $this->tokenStr = $tokenStr;
    }


}