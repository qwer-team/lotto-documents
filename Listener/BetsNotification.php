<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Qwer\LottoDocumentsBundle\Event\BetsEvent;
use \Qwer\LottoDocumentsBundle\Service\ClientApi;

class BetsNotification
{
    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\ClientApi 
     */
    private $clientApi;

    public function onEvent(BetsEvent $event)
    {
        $bets = $event->getBets();
        $token =$event->getToken();
        $client = $event->getToken()->getClient();
        $tokenStr =$token->getToken();
        
        $this->clientApi->sendBetsCreated($bets, $client,$tokenStr);
    }

    /**
     * 
     * @param \Qwer\LottoDocumentsBundle\Service\ClientApi  $clientApi
     */
    public function setClientsApi(ClientApi $clientApi)
    {
        $this->clientApi = $clientApi;
    }
}