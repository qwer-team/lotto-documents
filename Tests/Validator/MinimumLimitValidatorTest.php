<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoDocumentsBundle\Validator\MinimumLimit;
use Qwer\LottoDocumentsBundle\Validator\MinimumLimitValidator;

class MinimumLimitValidatorTest extends AbstractValidatorTest
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\MinimumLimitValidator 
     */
    private $validator;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Validator\MinimumLimit 
     */
    private $constraint;

    protected function setUp()
    {
        $this->validator = new MinimumLimitValidator();
        $context = $this->getContextMock();
        $this->validator->initialize($context);

        $this->constraint = new MinimumLimit();
    }

    public function validatorProvider()
    {
        return array(
            array(3, 10, 1),
            array(10, 10, 0),
            array(10.1, 10, 0),
            array(9.9, 10, 1),
            array(1000, 10, 0),
        );
    }

    /**
     * @dataProvider validatorProvider
     */
    public function testValidator($summa, $minimum, $count)
    {
        $this->summa = $summa;
        $this->minimum = $minimum;

        $bet = $this->getBetMock();

        $this->validator->validate($bet, $this->constraint);

        $this->assertEquals($count, count($this->violations));
    }

}