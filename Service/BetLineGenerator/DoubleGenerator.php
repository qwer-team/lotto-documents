<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Mass\GeneratorVariation;

class DoubleGenerator implements BetLineGenerator
{

    public function getBetLines(array $balls)
    {
        $n = 2;
        
        $generatorVariation = new GeneratorVariation();
        //Вернёт нам массив с ключами по которым мы будем генерить BetLine
        $keySort = $generatorVariation->some(count($balls), $n);

        $lines = new ArrayCollection();

        foreach ($keySort as $value) {

            for ($i = 0; $i < $n; $i++) {
                $ballMass[$i] = $balls[$value[$i] - 1];
            }

            $line = new BetLine();
            //$ballMass пример массива: Array ( [0] => 10 [1] => 12 [2] => 15 ) 
            $line->setBalls($ballMass);

            $lines->add($line);
        }
        return $lines;
    }

 
}