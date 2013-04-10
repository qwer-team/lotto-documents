<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\DoubleGenerator;

class DoubleGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(15, 25, 34, 42), 6),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\DoubleGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new DoubleGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

    public function gen2Provider()
    {
        return array(
            array(
                array(1, 2, 3),
                array(
                    array(1, 2),
                    array(1, 3),
                    array(2, 3),
                ),
            ),
            array(
                array(1, 2, 3, 4),
                array(
                    array(1, 2),
                    array(1, 3),
                    array(1, 4),
                    array(2, 3),
                    array(2, 4),
                    array(3, 4),
                ),
            ),
        );
    }

    /**
     * 
     * @dataProvider gen2Provider
     */
    public function testGen2($balls, $combinations)
    {
        $lineGenerator = new DoubleGenerator();

        $lines = $lineGenerator->getBetLines($balls);

        $linesCombinations = array();
        foreach ($lines as $line) {
            $linesCombinations[] = $line->getBalls();
        }

        foreach ($combinations as $combination) {
            $res = in_array($combination, $linesCombinations);
            $this->assertTrue($res);
        }
    }

}