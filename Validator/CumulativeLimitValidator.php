<?php

namespace Qwer\LottoDocumentsBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\EntityManager;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoDocumentsBundle\Service\CumulativeLimitStats;

class CumulativeLimitValidator extends ConstraintValidator
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Entity\BetLine 
     */
    private $betLine;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\CumulativeLimitStats
     */
    private $stats;

    public function validate($value, Constraint $constraint)
    {
        $this->betLine = $value;

        $ballsString = $this->betLine->getBallsString();
        $client = $this->betLine->getClient();
        $externalId = $this->betLine->getExternalUserId();
        $draw = $this->betLine->getLottoDraw();

        $possibleWin = $this->betLine->getRatedPossibleWin();
        if (!$this->checkLimitations($possibleWin, $client, $externalId, $draw, $ballsString)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    private function checkLimitations($possibleWin, Client $client, $externalId, Draw $draw, $ballsString)
    {
        $cummulatedAmount = $this->stats->getStats($client, $externalId, $draw, $ballsString);

        $cumLimit = $client->getCumulativeLimit();
        $ratedLimit = $client->getCurrency()->convertToMain($cumLimit);

        $totalAmount = $cummulatedAmount + $possibleWin;

        $this->stats->addAmount($ballsString, $possibleWin);
        return $totalAmount <= $ratedLimit;
    }

    public function setStats(CumulativeLimitStats $stats)
    {
        $this->stats = $stats;
    }

}