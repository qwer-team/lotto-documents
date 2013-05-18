<?php

namespace Qwer\LottoDocumentsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BetRepository extends EntityRepository
{

    public function getClientsBets($client, $draw, $status = 1)
    {
        $queryBuilder = $this->createQueryBuilder("bet");

        $queryBuilder->select("bet")
                ->where("bet.lottoClient = :client")
                ->andWhere("bet.lottoDraw = :draw")
                ->andWhere("bet.status = :status");
                
        $params = array(
            "client" => $client,
            "draw" => $draw,
            "status" => $status,
        );

        $queryBuilder->setParameters($params);
        return $queryBuilder->getQuery()->execute();
    }

}