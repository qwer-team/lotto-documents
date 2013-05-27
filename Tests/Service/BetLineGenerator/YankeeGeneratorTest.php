<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\YankeeGenerator;

class YankeeGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(15, 25, 34, 78), 11),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\YankeeGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new YankeeGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

}