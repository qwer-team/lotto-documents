<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Itc\DocumentsBundle\Listener\ContainerAware;
use Qwer\LottoDocumentsBundle\Event\BetsEvent;
use Itc\DocumentsBundle\Event\DocumentEvent;
use Qwer\LottoDocumentsBundle\Exception\FundsException;
use Qwer\LottoDocumentsBundle\Service\ClientApi;

class BetsCreationListener extends ContainerAware
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\ClientApi 
     */
    private $clientsApi;

    public function onEvent(BetsEvent $event)
    {
        $bets = $event->getBets();
        $token = $event->getToken();
        $this->em->getConnection()->beginTransaction();

        try {
            $summa = 0;
            foreach ($bets as $bet) {
                $summa += $bet->getSumma();

                $documentEvent = new DocumentEvent($bet);
                $this->dispatcher
                ->dispatch("approve.document.event", $documentEvent);
            }

            $this->em->flush();
            
            if (!$this->clientsApi->hasEnoughFunds($summa, $token)) {
                $translator = $this->container->get("translator");
                $exception = new FundsException($translator->trans("limit.message.funds", array(), 'validators'));
                throw $exception;
            }
            
             
            $client = $event->getToken()->getClient();
            $tokenStr =$token->getToken();
         
            $err=$this->clientsApi->sendBetsCreated ($bets, $client,$tokenStr);
             if ($err!=0) {
                $translator = $this->container->get("translator");
                $exception = new FundsException($translator->trans("limit.message.externalErr", array(), 'validators')." err# ".$err);
                throw $exception;
            }
            
            
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            throw $e;
        }
        
       // $this->dispatcher->dispatch("send.bets.create", $event);
    }
    
    public function setClientsApi(ClientApi $clientsApi)
    {
        $this->clientsApi = $clientsApi;
    }

}