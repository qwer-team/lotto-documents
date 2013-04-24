<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bet
 */
class Bet extends Document
{

    /**
     * @var string
     */
    protected $externalUserId;

    /**
     * @var \Qwer\LottoBundle\Entity\Client
     */
    protected $lottoClient;

    /**
     *
     * @var int 
     */
    protected $documentTypeId = 1;

    /**
     * @var boolean
     */
    protected $withBonus;

    /**
     *
     * @var array 
     */
    protected $balls;

    /**
     * @var \Qwer\LottoBundle\Entity\Draw
     */
    protected $lottoDraw;

    /**
     * Set externalUserId
     *
     * @param string $externalUserId
     * @return Bet
     */
    public function setExternalUserId($externalUserId)
    {
        $this->externalUserId = $externalUserId;

        return $this;
    }

    /**
     * Get externalUserId
     *
     * @return string 
     */
    public function getExternalUserId()
    {
        return $this->externalUserId;
    }

    /**
     * Set lotoClient
     *
     * @param \Qwer\LottoBundle\Entity\Client $lotoClient
     * @return Bet
     */
    public function setLotoClient(\Qwer\LottoBundle\Entity\Client $lotoClient = null)
    {
        $this->lottoClient = $lotoClient;

        return $this;
    }

    /**
     * Get lotoClient
     *
     * @return \Qwer\LottoBundle\Entity\Client 
     */
    public function getLotoClient()
    {
        return $this->lottoClient;
    }

    public function getDocumentTypeId()
    {
        return $this->documentTypeId;
    }

    public function setDocumentTypeId($documentTypeId)
    {
        $this->documentTypeId = $documentTypeId;
    }

    public function getBalls()
    {
        return $this->balls;
    }

    public function setBalls($balls)
    {
        $this->balls = $balls;
    }

    /**
     * Set withBonus
     *
     * @param boolean $withBonus
     * @return BetLine
     */
    public function setWithBonus($withBonus)
    {
        $this->withBonus = $withBonus;

        return $this;
    }

    /**
     * Get withBonus
     *
     * @return boolean 
     */
    public function getWithBonus()
    {
        return $this->withBonus;
    }

    public function addBetLine(BetLine $betLine)
    {
        $this->addDocumentLine($betLine);

        $summa = $this->getSumma1() + $betLine->getSumma();
        $this->setSumma1($summa);
    }

    /**
     * Set lottoDraw
     *
     * @param \Qwer\LottoBundle\Entity\Draw $lottoDraw
     * @return BetLine
     */
    public function setLottoDraw(\Qwer\LottoBundle\Entity\Draw $lottoDraw = null)
    {
        $this->lottoDraw = $lottoDraw;

        return $this;
    }

    /**
     * Get lottoDraw
     *
     * @return \Qwer\LottoBundle\Entity\Draw 
     */
    public function getLottoDraw()
    {
        return $this->lottoDraw;
    }

    public function setSumma($summa)
    {
        $this->summa1 = $summa;
    }

    public function getSumma()
    {
        return $this->summa1;
    }

    public function resetLines()
    {
        $lines = $this->getDocumentLines();
        $this->documentLines = new \Doctrine\Common\Collections\ArrayCollection();

        $newLines = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($lines as $line) {
            $newLine = clone($line);
            $newLine->setDocument($this);
            $newLines->add($newLine);
        }
        $this->documentLines = $newLines;
    }
    
    public function addWonAmount($amount){
        $this->summa2 += $amount;
    }

}
