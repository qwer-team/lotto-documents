<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoDocumentsBundle\Validator\CumulativeLimitValidator;
use Qwer\LottoDocumentsBundle\Validator\CumulativeLimit;

class CumulativeLimitValidatorTest extends AbstractValidatorTest
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\CumulativeLimitValidator 
     */
    private $validator;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\CumulativeLimit
     */
    private $constraint;

    /**
     *
     * @var array 
     */
    private $stats = array();

    /**
     *
     * @var integer 
     */
    private $statsStart = 10;

    protected function setUp()
    {
        parent::setUp();


        $this->validator = new CumulativeLimitValidator();

        $context = $this->getContextMock();
        $this->validator->initialize($context);

        $stats = $this->getStatsMock();
        $this->validator->setStats($stats);

        $this->constraint = new CumulativeLimit();
    }

    private function getStatsMock()
    {
        $stub = $this->getMock("Qwer\LottoDocumentsBundle\Service\CumulativeLimitStats");

        $stub->expects($this->any())
        ->method("getStats")
        ->withAnyParameters()
        ->will($this->returnCallback(array($this, 'getStatsCallback')));

        $stub->expects($this->any())
        ->method("addAmount")
        ->withAnyParameters()
        ->will($this->returnCallback(array($this, 'addAmountCallback')));

        return $stub;
    }

    public function getStatsCallback($client, $externalId, $draw, $ballsString)
    {
        if (!isset($this->stats[$ballsString])) {
            $this->stats[$ballsString] = $this->statsStart;
        }

        return $this->stats[$ballsString];
    }

    public function addAmountCallback($ballsString, $amount)
    {
        $this->stats[$ballsString] += $amount;
    }

    public function validatorProvider()
    {
        return array(
            array(50, 100, 100, 1),
            array(50, 50, 100, 0),
            array(50, 51, 100, 1),
            array(10, 80, 100, 0),
        );
    }

    /**
     * @dataProvider validatorProvider
     */
    public function testValidator($amount, $start, $limit,  $cnt)
    {
        $this->summa = $amount;
        $this->rate = 2;
        $this->statsStart = $start;
        $this->cumLimit = $limit;
        
        $bet = $this->getBetMock();

        $this->validator->validate($bet, $this->constraint);

        $this->assertEquals($cnt, count($this->violations));
    }

}