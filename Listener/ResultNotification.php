<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Qwer\LottoDocumentsBundle\Event\BetsEvent;
use \Qwer\LottoDocumentsBundle\Service\ClientApi;

class ResultNotification
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\ClientApi 
     */
    private $clientApi;

    public function onEvent(BetsEvent $event)
    {
        $bets = $event->getBets();
        $client = $event->getClient();
        
        $this->clientApi->sendBetsResult($bets, $client);
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