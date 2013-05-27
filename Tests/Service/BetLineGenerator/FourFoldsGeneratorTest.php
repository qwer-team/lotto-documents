<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\FourFoldsGenerator;

class FourFoldsGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(15, 25, 34, 78, 6 ,7, 89), 35),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\FourFoldsGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new FourFoldsGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

}