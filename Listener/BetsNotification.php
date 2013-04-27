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
        $client = $event->getToken()->getClient();
        
        $this->clientApi->sendBetsCreated($bets, $client);
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