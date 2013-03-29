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

    public function setSumma($summa)
    {
        $this->summa1 = $summa;
    }

    public function getSumma()
    {
        return $this->summa1;
    }

}
