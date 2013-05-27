<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoDocumentsBundle\Validator\BallsCountValidator;
use Qwer\LottoDocumentsBundle\Validator\BallsCount;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;
use Qwer\LottoBundle\Entity\BetType;

class BallsCountValidatorTest extends AbstractValidatorTest
{

    /**
     * @var \Qwer\LottoDocumentsBundle\Validator\BallsCountValidator
     */
    private $validator;

    /**
     * @var \Qwer\LottoDocumentsBundle\Validator\BallsCount
     */
    private $contraint;

    protected function setUp()
    {
        $this->validator = new BallsCountValidator();
        $this->contraint = new BallsCount();

        $context = $this->getContextMock();
        $this->validator->initialize($context);
    }

    public function validatorTestProvider()
    {
        return array(
            array(array(1, 2, 3), array(2), 1),
            array(array(1, 2, 3), array(3), 0),
            array(array(1, 2, 3), array(6, 7, 8), 1),
            array(array(1, 2, 3), array(1, 2, 3, 4, 5, 6, 7, 8), 0),
        );
    }

    /**
     * @covers \Qwer\LottoDocumentsBundle\Validator\BallsCountValidator::validate
     * @dataProvider validatorTestProvider
     */
    public function testValidator($balls, $availableCounts, $count)
    {
        $rawBet = new RawBet();
        $rawBet->setBalls($balls);

        $betType = new BetType();
        $betType->setAvailableBallsCount($availableCounts);

        $rawBet->setBetType($betType);
        $this->validator->validate($rawBet, $this->contraint);

        $this->assertEquals($count, count($this->violations));
    }

}