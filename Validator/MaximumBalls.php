<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaximumBalls extends Constraint
{
    public $message = "";
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return "maximum_balls";
    }

    public function getMessage()
    {
        return $this->message;
    }
    
}