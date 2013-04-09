<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;

class DoubleGenerator implements BetLineGenerator
{

    public function getBetLines(array $balls)
    {
        $n = 2;
        //Вернёт нам массив с ключами по которым мы будем генерить BetLine
        $keySort = $this->some(count($balls), $n);

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

    public function some($m, $n)
    {
        //Количество шаров выбраным пользователем
        $countBalls = $m;
        //Количество шаров в комбинации
        $ballInArray = $n;
        $vector = array();
        $outArray = array();

        $delta = $countBalls - $ballInArray;

        $num = 0;
        while ($num < $ballInArray) {
            $vector[$num] = $num;
            $num++;
        }

        $combination_count = 0;
        $is_nocycle = 0;
        $num = 0;
        while (($num != $delta) || ($is_nocycle == 0)) {
            $num = 0;
            while ($num < $countBalls) {
                $combination_count++;
                $i = 0;
                $combination = array();
                while ($i < $ballInArray) {
                    $num = $vector[$i] + 1;
                    $combination[$i] = $num;
                    $i++;
                }
                $outArray[] = $combination;
                $vector[$ballInArray - 1] = $vector[$ballInArray - 1] + 1;
                $num = $vector[$ballInArray - 1];
            }

            $is_nocycle = 1;
            $i = $ballInArray - 2;
            while ($i >= 0) {
                $num = $vector[$i];
                if ($num < $delta + $i) {
                    $is_nocycle = 0;
                    $vector[$i] = $vector[$i] + 1;
                    $j = $i + 1;
                    while ($j < $ballInArray) {
                        $num = $vector[$j - 1];
                        $vector[$j] = $num + 1;
                        $j++;
                    }
                    break;
                }
                $i--;
            }
            $num = $vector[0];
        }
        return $outArray;
    }

}