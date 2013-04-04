<?php

namespace Qwer\LottoDocumentsBundle\Service;

class CumulativeLimitStats
{

    /**
     *
     * @var array 
     */
    private $stats = array();

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Repository\RestRepository 
     */
    private $restRepo;

    public function setEntityManager(EntityManager $em)
    {
        $repo = $em->getRepository("QwerLottoDocumentsBundle:Rest");
        $this->restRepo = $repo;
    }

    public function getStats(Client $client, $externalId, Draw $draw, $ballsString)
    {
        if (!isset($this->stats[$ballsString])) {
            $amount = $this->restRepo->getCumulativeAmount($client, $externalId, $draw, $ballsString);
            $this->stats[$ballsString] = $amount;
        }

        return $this->stats[$ballsString];
    }

    public function addAmount($ballsString, $amount)
    {
        $this->stats[$ballsString] += $amount;
    }

}