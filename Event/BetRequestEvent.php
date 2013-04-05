<?php

namespace Qwer\LottoDocumentsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;

class BetRequestEvent extends Event
{
    /**
     *
     * @var Body 
     */
    private $body;
    
    /**
     * 
     * @return Body
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * 
     * @param Body $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}