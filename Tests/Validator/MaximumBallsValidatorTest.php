<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoDocumentsBundle\Validator\MaximumBallsValidator;
use Qwer\LottoDocumentsBundle\Validator\MaximumBalls;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;
use Qwer\LottoBundle\Entity\BetType;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;
use Qwer\LottoBundle\Entity\Type;
use Qwer\LottoBundle\Entity\Time;
use Doctrine\Common\Collections\ArrayCollection;

class MaximumBallsValidatorTest extends AbstractValidatorTest
{
    
    /**
     * @var \Qwer\LottoDocumentsBundle\Validator\MaximumBallsValidator
     */
    private $validator;

    /**
     * @var \Qwer\LottoDocumentsBundle\Validator\MaximumBalls
     */
    private $contraint;
    
    protected function setUp()
    {
        $this->validator = new MaximumBallsValidator();
        $this->contraint = new MaximumBalls();
        
        $context = $this->getContextMock();
        $this->validator->initialize($context);
    }
    public function validatorTestProvider()
    {
        return array(
            array(array(1, 2, 4, 47), 45, 1),
            array(array(1, 2, 4, 44), 45, 0),
        );
    }
    
    /**
     * @dataProvider validatorTestProvider
     */
    public function testValidator($balls, $total, $cnt)
    {
        
        $type = new Type();
        $type->setTottalBalls($total);
        
        $time = new Time();
        $time->setLottoType($type);
        
        $body = new Body();
        $body->setLottoTime($time);
        
        $rawBets = new ArrayCollection();
        $rawBet = new RawBet();
        $rawBet->setBalls($balls);
        $rawBets->add($rawBet);
        
        $body->setRawBets($rawBets);
        
        $this->validator->validate($body, $this->contraint);
        
        $this->assertEquals($cnt, count($this->violations));
    }
}