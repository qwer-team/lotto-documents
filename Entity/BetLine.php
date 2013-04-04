<?php

namespace Qwer\LottoDocumentsBundle\Entity;

/**
 * BetLine
 */
class BetLine extends DocumentLine
{

    /**
     * @var string
     */
    protected $balls;

    /**
     *
     * @var float 
     */
    protected $odd;

    /**
     * Set balls
     *
     * @param string $balls
     * @return BetLine
     */
    public function setBalls($balls)
    {
        $this->balls = $balls;

        return $this;
    }

    /**
     * Get balls
     *
     * @return string 
     */
    public function getBalls()
    {
        return $this->balls;
    }

    public function getBallsString()
    {
        sort($this->balls);
        return implode("_", $this->balls);
    }

    public function setSumma($summa)
    {
        $this->summa1 = $summa;
    }

    public function getSumma()
    {
        return $this->summa1;
    }

    public function getRatedSumma()
    {
        $currency = $this->getCurrency();
        return $currency->convertToMain($this->getSumma());
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->getDocument()->gteClient();
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Type
     */
    public function getLottoType()
    {
        return $this->getDocument()->getLottoDraw()->getLottoTime()->getLottoType();
    }

    /**
     * 
     * @return \Qwer\LottoDocumentsBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->getDocument()->getCurrency();
    }

    public function getExternalUserId()
    {
        return $this->getDocument()->getExternalUserId();
    }

    public function getLottoDraw()
    {
        return $this->getDocument()->getLottoDraw();
    }

    public function getOdd()
    {
        return $this->odd;
    }

    public function setOdd($odd)
    {
        $this->odd = $odd;
    }

    public function getPossibleWin()
    {
        $possibleWin = $this->getSumma() * ($this->getOdd() - 1);
        return $possibleWin;
    }

    public function getRatedPossibleWin()
    {
        return $this->getCurrency()->getRateToMain($this->getPossibleWin());
    }

}
