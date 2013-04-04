<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;
use Qwer\LottoBundle\Entity\BetType;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Qwer\LottoBundle\Entity\Type;
use Qwer\LottoBundle\Entity\Client;

class BetMapper extends ContainerAware
{

    /**
     *
     * @var DrawFinder 
     */
    private $drawFinder;

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\RateService 
     */
    private $rateService;

    /**
     * 
     * @param \Qwer\LottoDocumentsBundle\Entity\Request\Body $body
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBets(Body $body)
    {
        $currency = $body->getCurrency();
        $externalId = $body->getExternalId();
        $client = $body->getClient();
        $withBonus = $body->getWithBonus();
        $drawNum = $body->getDrawNum();

        $betsPrototypes = new ArrayCollection();

        foreach ($body->getRawBets() as $rawBet) {
            $bet = $this->getBet($rawBet);
            $bet->setCurrency($currency);
            $bet->setExternalUserId($externalId);
            $bet->setLotoClient($client);
            $bet->setWithBonus($withBonus);

            $betsPrototypes->add($bet);
        }

        $lottoTime = $body->getLottoTime();
        $lottoType = $lottoTime->getLottoType();

        foreach ($betsPrototypes as $betPrototype) {
            $betLines = $betPrototype->getDocumentLines();
            foreach($betLines as $line) {
                $balls = $line->getBalls();
                $odd = $this->rateService->getRate($balls, $withBonus, $lottoType, $client);
                $line->setOdd($odd);
            }
        }

        $draws = $this->drawFinder->getDraws($lottoTime, $drawNum);

        $bets = new ArrayCollection();

        foreach ($draws as $draw) {
            foreach ($betsPrototypes as $betPrototype) {
                $bet = clone($betPrototype);
                $bet->setLottoDraw($draw);
                $bets->add($bet);
            }
        }

        return $bets;
    }

    /**
     * 
     * @param \Qwer\LottoDocumentsBundle\Entity\Request\RawBet $rawBet
     * @return \Qwer\LottoDocumentsBundle\Entity\Bet
     */
    private function getBet(RawBet $rawBet)
    {
        $balls = $rawBet->getBalls();
        $summa = $rawBet->getSumma();

        $bet = new Bet();
        $bet->setBalls($balls);

        $betType = $rawBet->getBetType();
        $generator = $this->getBetLineGenerator($betType);

        $betLines = $generator->getBetLines($balls);

        foreach ($betLines as $betLine) {
            $betLine->setSumma($summa);
            $bet->addBetLine($betLine);
        }

        return $bet;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\BetType $betType
     * @return BetLineGenerator
     * @throws \ResourceNotFoundException
     */
    private function getBetLineGenerator(BetType $betType)
    {
        $serviceId = sprintf("bet_line.generator.%s", $betType->getCode());

        if (!$this->container->has($serviceId)) {
            $message = sprintf("Service \"%s\" was not found.", $serviceId);
            throw new ResourceNotFoundException($message);
        }

        $service = $this->container->get($serviceId);
        return $service;
    }

    public function setDrawFinder($drawFinder)
    {
        $this->drawFinder = $drawFinder;
    }

    public function setRateService(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

}