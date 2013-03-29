<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Itc\DocumentsBundle\Listener\ContainerAware;
use Qwer\LottoDocumentsBundle\Event\BetsEvent;
use Itc\DocumentsBundle\Event\DocumentEvent;
use Qwer\LottoDocumentsBundle\Exception\FundsException;

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
        $client = $event->getClient();
        $this->em->getConnection()->beginTransaction();

        try {
            $summa = 0;
            foreach ($bets as $bet) {
                $summa = $bet->getSumma();

                $documentEvent = new DocumentEvent($bet);
                $this->dispatcher
                ->dispatch("approve.document.event", $documentEvent);
            }

            $this->em->flush();
            
            if (!$this->clientsApi->hasEnoughFunds($summa, $client)) {
                $exception = new FundsException();
                throw $exception;
            }
            
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
            throw $e;
        }
    }

}