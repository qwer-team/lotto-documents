<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Math\GeneratorVariation;

abstract class AbstractVariationsGenerator implements BetLineGenerator
{
    protected $n = null;
    
   public function getBetLines(array $balls)
    {
        $generatorVariation = new GeneratorVariation();
        $combinations = $generatorVariation->combinations($balls, $this->n);

        $lines = new ArrayCollection();

        foreach($combinations as $combination) {
            $line = new BetLine();
            $line->setBalls($combination);
            $lines->add($line);
        }

        return $lines;
    }

}