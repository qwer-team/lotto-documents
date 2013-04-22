<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BallsCount extends Constraint
{
    public $message = "";
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return "balls_count";
    }

    public function getMessage()
    {
        return $this->message;
    }
    
}