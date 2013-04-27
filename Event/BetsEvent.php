<?php

namespace Qwer\LottoDocumentsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Qwer\UserBundle\Entity\Token;

class BetsEvent extends Event
{
    private $bets;
    
    /**
     *
     * @var \Qwer\UserBundle\Entity\Token 
     */
    private $token;
    
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
     * @return \Qwer\UserBundle\Entity\Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 
     * @param \Qwer\UserBundle\Entity\Token $token
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
    }
    
}