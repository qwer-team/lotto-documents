<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\PatentGenerator;

class PatentGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function generatorProvider()
    {
        return array(
            array(array(15, 25, 34), 7),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Service\BetLineGenerator\PatentGenerator::getBetLines
     * @dataProvider generatorProvider
     */
    public function testGenerator($in, $count)
    {

        $lineGenerator = new PatentGenerator();

        $lines = $lineGenerator->getBetLines($in);

        $this->assertEquals($count, count($lines));
    }

}