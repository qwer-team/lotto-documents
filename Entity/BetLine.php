<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Itc\DocumentsBundle\Entity\Document\DocumentLine;

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
     * @var boolean
     */
    protected $withBonus;

    /**
     * @var \Qwer\LottoBundle\Entity\Draw
     */
    protected $lottoDraw;


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
}
