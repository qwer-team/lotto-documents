<?php

namespace Qwer\LottoDocumentsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
USE Qwer\LottoBundle\Entity\Client;

class BetsEvent extends Event
{
    private $bets;
    
    /**
     *
     * @var \Qwer\LottoBundle\Entity\Client 
     */
    private $client;
    
    public function getBets()
    {
        return $this->bets;
    }

    public function setBets($bets)
    {
        $this->bets = $bets;
    }
    
    /**
     * 
     * @return \Qwer\LottoBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

}