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
        $summa = $this->betLine->getSumma();

        $client = $this->betLine->getClient();
        $lottoType = $this->betLine->getLottoType();
        $balls = $this->betLine->getBalls();
        $currency = $this->betLine->getCurrency();

        $rate = $this->rateService->getRate($balls, $lottoType, $client);

        if (!$this->checkLimitation($summa, $rate, $currency, $client)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    private function checkLimitation($summa, $rate, Currency $currency, Client $client)
    {
        $possibleWin = $currency->convertToMain($summa) * ($rate - 1);

        $clientsCur = $client->getCurrency();
        $staticLimit = $clientsCur->convertToMain($client->getStaticLimit());

        return $possibleWin <= $staticLimit;
    }

    public function setRateService(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

}