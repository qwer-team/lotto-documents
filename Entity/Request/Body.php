<?php

namespace Qwer\LottoDocumentsBundle\Entity\Request;

use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Currency;
use Qwer\LottoBundle\Entity\Type;

class Body
{

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

    private $bets;
    function __construct()
    {
        $this->rawBets = new ArrayCollection();
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Type
     */
    public function getLottoType()
    {
        return $this->lottoType;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Type $lottoType
     */
    public function setLottoType(Type $lottoType)
    {
        $this->lottoType = $lottoType;
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

    public function getBets()
    {
        return $this->bets;
    }

    public function setBets($bets)
    {
        $this->bets = $bets;
    }
}