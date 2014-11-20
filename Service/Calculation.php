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
$msg="";
        $clients = $this->findClients();
        foreach ($clients as $client) {
           $msg.=$this->calculateForClient($client);
        }
        $this->draw->setLottoStatus(2);
        $this->em->flush();
        return " - ".$msg;
    }
    
    public function rallback(Draw $draw){
        $this->draw = $draw;

        $clients = $this->findClients();
        foreach ($clients as $client) {
            $this->rollbackForClient($client);
        }
        $this->draw->setLottoStatus(1);
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
    private function rollbackForClient(Client $client)
    {
        $bets = $this->getClientsBets($client, 2);

        if (count($bets)) {
            foreach ($bets as $bet) {
                $this->rallbackBet($bet);
            }
            $resultEvent = new BetsEvent();
            $resultEvent->setBets($bets);
            $resultEvent->setClient($client);
            $this->dispatcher->dispatch("send.bets.rollback", $resultEvent);
        }
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
       return "b- ".count($bets);
    }

    
    private function getClientsBets($client, $status = 1)
    {
        $betRepo = "QwerLottoDocumentsBundle:Bet";
        
        $repo = $this->em->getRepository($betRepo);
        $bets = $repo->getClientsBets($client, $this->draw, $status);
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
    
    private function rallbackBet(Bet $bet)
    {
        foreach ($bet->getDocumentLines() as $line) {
            $line->setWonAmount(0);
        }
        $bet->setStatus(1);
        $bet->setSumma2(0);
        
        $event = new DocumentEvent($bet);
        $this->dispatcher->dispatch("return.document.event", $event);
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
     
        for ($i = 0; $i < count($resultsBalls); $i++) {
            $resultsBalls[$i] = ltrim($resultsBalls[$i],"0"); 
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