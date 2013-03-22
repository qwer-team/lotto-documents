<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;

class BetMapping extends ContainerAware
{

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
        
        $bets = new ArrayCollection();

        foreach ($body->getRawBets() as $rawBet) {
            $bet = $this->getBet($rawBet);
            $bet->setCurrency($currency);
            $bet->setExternalUserId($externalId);
            $bet->setLotoClient($client);
            
            $bets->add($bet);
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
        $bet = new Bet();
        
    }

}