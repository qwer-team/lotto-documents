<?php

namespace Qwer\LottoDocumentsBundle\Entity\Request;


class RawBet
{
    /**
     *
     * @var array 
     */
    private $balls = array(1,2,3,4,5);
    
    /**
     *
     * @var \Qwer\LottoBundle\Form\BetType 
     */
    private $betType;
    
    /**
     *
     * @var type 
     */
    private $summa;
    
    public function getBalls()
    {
        return $this->balls;
    }

    public function setBalls($balls)
    {
        $this->balls = $balls;
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Entity\BetType
     */
    public function getBetType()
    {
        return $this->betType;
    }

    public function setBetType($betType)
    {
        $this->betType = $betType;
    }

    public function getSumma()
    {
        return $this->summa;
    }

    public function setSumma($summa)
    {
        $this->summa = $summa;
    }

}