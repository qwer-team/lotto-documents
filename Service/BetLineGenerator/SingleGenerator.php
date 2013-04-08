<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;

class SingleGenerator implements BetLineGenerator
{

    public function getBetLines(array $balls)
    {
        $lines = new ArrayCollection();

        foreach($balls as $ball) {
            $line = new BetLine();
            $line->setBalls(array($ball));

            $lines->add($line);
        }
        

        return $lines;
    }

}