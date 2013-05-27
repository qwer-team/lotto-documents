<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\SingleGenerator;

class SingleGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(1, 2, 3), 3),
            array(array(1, 2, 3, 4), 4),
            array(array(1, 2, 3, 4, 5), 5),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\SingleGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new SingleGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

}