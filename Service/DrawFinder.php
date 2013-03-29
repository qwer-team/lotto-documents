<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Time;
use Doctrine\Common\Collections\ArrayCollection;

class DrawFinder
{

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Time $type
     * @param integer $drawNum
     */
    public function getDraws(Time $type, $drawNum)
    {
        $draws = new ArrayCollection();

        return $draws;
    }

}