<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Itc\DocumentsBundle\Event\DocumentEvent;
use Qwer\LottoDocumentsBundle\Event\BetsEvent;

class Calculation extends ContainerAware
{
    /**
     * Тираж который будет расчитан.
     * @var \Qwer\LottoBundle\Entity\Draw 
     */
    private $draw;

    /**
     * @param \Qwer\LottoBundle\Entity\Draw $draw
     */
    public function calculate(Draw $draw)
    {
        $this->draw = $draw;

        $clients = $this->findClients();
        foreach ($clients as $client) {
            $this->calculateForClient($client);
        }
        $this->em->flush();
    }
    
    private function findClients()
    {
        $clientsRepo = "QwerLottoBundle:Client";
        $repo = $this->em->getRepository($clientsRepo);
        
        $clients = $repo->findClients($this->draw);
        return $clients;
    }

            /**
     * 
     * @param \Qwer\LottoBundle\Entity\Client $client
     */
    private function calculateForClient(Client $client)
    {
        $bets = $this->getClientsBets($client);

        if (count($bets)) {
            foreach ($bets as $bet) {
                $this->calculateBet($bet);
            }

            $resultEvent = new BetsEvent();
            $resultEvent->setBets($bets);
            $resultEvent->setClient($client);
            $this->dispatcher->dispatch("send.bets.result", $resultEvent);
        }
    }

    
    private function getClientsBets($client)
    {
        $betRepo = "QwerLottoDocumentsBundle:Bet";
        
        $repo = $this->em->getRepository($betRepo);
        $bets = $repo->getClientsBets($client, $this->draw);
        return $bets;
    }

    
    private function calculateBet(Bet $bet)
    {
        foreach ($bet->getDocumentLines() as $line) {
            $this->calculateBetLine($line);
        }
        $bet->setStatus(2);
        
        $event = new DocumentEvent($bet);
        $this->dispatcher->dispatch("approve.document.event", $event);
    }

    private function calculateBetLine(BetLine $betline)
    {
        $results = $this->draw->getResult();
        
        $withBonus = $betline->getDocument()->getWithBonus();
        if ($withBonus) {
            $resultsBalls = $results->getAllBalls();
        } else {
            $resultsBalls = $results->getResult();
        }
        
        $balls = $betline->getBalls();
        $win = true;
        foreach($balls as $ball){
            if(!in_array($ball, $resultsBalls)){
                $win = false;
                break;
            }
        }
        
        $winSum = 0;
        if($win){
            $winSum = $betline->getSumma() * $betline->getOdd();
        }
        
        $betline->setWonAmount($winSum);
    }

}