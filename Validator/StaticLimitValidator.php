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
            $currency = $this->betLine->getCurrency();
            $limit = $currency->convertFromMain($this->getRatedStaticLimit($client));
            $params = array(
                "%number1%" => $limit
            );
            $this->context->addViolation($constraint->getMessage(), $params);
        }
    }

    private function getRatedStaticLimit(Client $client){
        $clientsCur = $client->getCurrency();
        $staticLimit = $clientsCur->convertToMain($client->getStaticLimit());
        return $staticLimit;
    }

    private function checkLimitation($possibleWin, Client $client)
    {
        $staticLimit = $this->getRatedStaticLimit($client);
        return $possibleWin <= $staticLimit;
    }

    public function setRateService(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

}