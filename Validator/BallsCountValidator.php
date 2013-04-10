<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class BallsCountValidator extends ConstraintValidator
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\Request\RawBet 
     */
    private $rawBet;

    public function validate($value, Constraint $constraint)
    {
        $this->rawBet = $value;

        $betType = $this->rawBet->getBetType();
        $balls = $this->rawBet->getBalls();

        $ballsCount = count($balls);

        $availableCounts = $betType->getAvailableBallsCount();

        if (!in_array($ballsCount, $availableCounts)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

}