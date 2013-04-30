<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Qwer\LottoDocumentsBundle\Service\RateService;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Currency;

class StaticLimitValidator extends ConstraintValidator
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\RateService 
     */
    private $rateService;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\BetLine 
     */
    private $betLine;

    public function validate($value, Constraint $constraint)
    {
        $this->betLine = $value;

        $client = $this->betLine->getClient();
        $possibleWin = $this->betLine->getRatedPossibleWin();

        if (!$this->checkLimitation($possibleWin, $client)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    private function checkLimitation($possibleWin, Client $client)
    {
        $clientsCur = $client->getCurrency();
        $staticLimit = $clientsCur->convertToMain($client->getStaticLimit());
        return $possibleWin <= $staticLimit;
    }

    public function setRateService(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

}