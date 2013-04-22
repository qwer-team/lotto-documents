<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\LuckyGenerator;

class LuckyGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(15, 25, 34, 78), 15),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\LuckyGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new LuckyGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

}