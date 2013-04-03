<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Qwer\LottoDocumentsBundle\Entity\Currency;
use Qwer\LottoBundle\Entity\Client;

class MinimumLimitValidator extends ConstraintValidator
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\BetLine 
     */
    private $betLine;

    public function validate($value, Constraint $constraint)
    {
        $this->betLine = $value;

        $ratedSumma = $this->betLine->getRatedSumma();
        $client = $this->betLine->getClient();

        if (!$this->checkLimitation($ratedSumma, $client)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    public function checkLimitation($ratedSumma, Client $client)
    {
        $clientsCur = $client->getCurrency();
        $ratedLimit = $clientsCur->convertToMain($client->getMinimumLimit());
        
        return $ratedSumma >= $ratedLimit;
    }

}