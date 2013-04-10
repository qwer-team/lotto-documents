<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Math\GeneratorVariation;
/**
 * Description of AbstractComplexGenerator
 *
 * @author root
 */
abstract class AbstractComplexGenerator
{
    public function getBetLines(array $balls)
    {

        $generatorVariation = new GeneratorVariation();
        //Вернёт нам массив и мы будем генерить BetLine
        $keySort = $generatorVariation->allCombinations($balls);

        $lines = new ArrayCollection();

        foreach ($keySort as $value) {
            $line = new BetLine();
            //$value пример массива: Array ( [0] => 10 [1] => 12 [2] => 15 ) 
            $line->setBalls($value);

            $lines->add($line);
        }
        return $lines;
    }
}