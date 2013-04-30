<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MinimumLimit extends Constraint
{
    public $message = "yopt";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return "minimum_limit";
    }

    public function getMessage()
    {
        return $this->message;
    }
}