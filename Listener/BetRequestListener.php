<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Qwer\LottoDocumentsBundle\Event\BetRequestEvent;
use Qwer\LottoDocumentsBundle\Service\BetMapper;
use Doctrine\ORM\EntityManager;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\ConstraintViolationList;
use Qwer\LottoDocumentsBundle\Exception\BetRequestException;
use Qwer\LottoDocumentsBundle\Event\BetsEvent;

class BetRequestListener extends ContainerAware
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\BetMapper  
     */
    private $mapper;

    /**
     *
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;

    public function onEvent(BetRequestEvent $event)
    {
        $body = $event->getBody();

        $bets = $this->mapper->getBets($body);

        $violations = array();
        foreach ($bets as $bet) {
            $betViolations = $this->validator->validate($bet);
            if (count($betViolations) > 0) {
                $ballsString = implode(",", $bet->getBalls());
                $violations[$ballsString] = $betViolations;
            }
        }

        if(count($violations) > 0) {
            $exception = new BetRequestException();
            $exception->setViolations($violations);
            
            throw $exception;
        }
        
        $betsEvent = new BetsEvent();
        $betsEvent->setBets($bets);
        
        $this->dispatcher->dispatch("create.bets.event", $betsEvent);
    }

}