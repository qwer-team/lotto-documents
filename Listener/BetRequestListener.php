<?php

namespace Qwer\LottoDocumentsBundle\Listener;

use Qwer\LottoDocumentsBundle\Event\BetRequestEvent;
use Qwer\LottoDocumentsBundle\Service\BetMapper;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Symfony\Component\Validator\Validator;
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
        $token = $this->em->getRepository("QwerUserBundle:Token")
                      ->findOneByToken($body->getTokenStr());
        $body->setToken($token);
        $client = $token->getClient();

        $bets = $this->mapper->getBets($body);

        $violations = array();
        foreach ($bets as $bet) {
            $betViolations = $this->validator->validate($bet);

            if (count($betViolations) > 0) {
                $ballsString = implode(",", $bet->getBalls());
                $violations[$ballsString] = $betViolations;
            }

            foreach($bet->getDocumentLines() as $line) {
                $betLineViolations = $this->validator->validate($line);
                
                if (count($betLineViolations) > 0) {
                    $ballsString = implode(",", $line->getBalls());
                    $violations[$ballsString] = $betLineViolations;
                }
            }
        }

        if (count($violations) > 0) {
            $exception = new BetRequestException();
            $exception->setViolations($violations);

            throw $exception;
        }

        $betsEvent = new BetsEvent();
        $betsEvent->setBets($bets);
        $betsEvent->setClient($client);

        $this->dispatcher->dispatch("create.bets.event", $betsEvent);
    }

    public function setMapper(BetMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

}