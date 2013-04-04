<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoDocumentsBundle\Validator\StaticLimitValidator;
use Qwer\LottoDocumentsBundle\Validator\StaticLimit;
use Itc\DocumentsBundle\Tests\Service\MockFactory;

class StaticLimitValidatorTest extends AbstractValidatorTest
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\StaticLimitValidator 
     */
    private $validator;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\StaticLimit 
     */
    private $constraint;

    protected function setUp()
    {
        parent::setUp();
        
        $this->validator = new StaticLimitValidator();

        $context = $this->getContextMock();
        $this->validator->initialize($context);

        $rateService = $this->getRateServiceMock();
        $this->validator->setRateService($rateService);

        $this->constraint = new StaticLimit();

        $this->violations = array();
    }

    private function getRateServiceMock()
    {
        $stub = $this->getMock("Qwer\LottoDocumentsBundle\Service\RateService");

        $stub->expects($this->any())
        ->method("getRate")
        ->withAnyParameters()
        ->will($this->returnCallback(array($this, 'getRate')));

        return $stub;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function validatorProvider()
    {
        return array(
            array(50, 1000, 2.2, 0),
            array(20, 10000, 1.8, 0),
            array(1000, 1000, 2, 0),
            array(500, 1000, 3.5, 1),
            array(1001, 1000, 2, 1),
        );
    }

    /**
     * @dataProvider validatorProvider
     * @param float $summa
     * @param float $limit
     * @param float $rate
     * @param integer $errorCnt
     */
    public function testValidator($summa, $limit, $rate, $errorCnt)
    {
        $this->summa = $summa;
        $this->limit = $limit;
        $this->rate = $rate;

        $bet = $this->getBetMock();

        $this->validator->validate($bet, $this->constraint);

        $this->assertEquals($errorCnt, count($this->violations));
    }

}