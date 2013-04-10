<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Math\GeneratorVariation;

class AccumulatorGenerator implements BetLineGenerator
{

    public function getBetLines(array $balls)
    {

        $generatorVariation = new GeneratorVariation();

        $lines = new ArrayCollection();
        $line = new BetLine();
        $line->setBalls($balls);
        $lines->add($line);

        return $lines;
    }

}