<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Entity\BetLine;

class Calculation
{

    private $draw;

    public function calculate(Draw $draw)
    {
        $this->draw = $draw;

        $this->loadBets();
        $clients = $this->findClients();
        foreach ($clients as $client) {
            $this->calculateForClient($client);
        }
    }

    private function loadBets()
    {
        
    }

    private function calculateForClient(Client $client)
    {
        $bets = $this->getClientsBets($client);

        foreach ($bets as $bet) {
            $this->calculateBet($bet);
        }

        $this->prepeareToSend($client, $bet);
    }

    private function prepeareToSend(Client $client, Bet $bet)
    {
        
    }

    private function calculateBet(Bet $bet)
    {
        foreach($bet->getDocumentLines() as $line) {
            $this->calculateBetLine($line);
        }
    }

    private function calculateBetLine(BetLine $betline)
    {
        
    }

}