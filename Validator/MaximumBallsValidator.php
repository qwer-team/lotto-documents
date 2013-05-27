<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class MaximumBallsValidator extends ConstraintValidator
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\Request\Body 
     */
    private $body;

    public function validate($value, Constraint $constraint)
    {
        $this->body = $value;
        $type = $this->body->getLottoTime()->getLottoType();

        $total = $type->getTottalBalls();
        $availableBalls = range(1, $total);

        $raws = $this->body->getRawBets();

        foreach ($raws as $raw) {
            $balls = $raw->getBalls();

            if(count(array_diff($balls, $availableBalls))) {
                $this->context->addViolation($constraint->getMessage());
            }
        }
    }

}