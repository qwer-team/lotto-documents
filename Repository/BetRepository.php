<?php

namespace Qwer\LottoDocumentsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BetRepository extends EntityRepository
{

    public function getClientsBets($client, $draw)
    {
        $queryBuilder = $this->createQueryBuilder("bet");

        $queryBuilder->select("bet")
                ->where("bet.lottoClient = :client")
                ->andWhere("bet.lottoDraw = :draw")
                ->andWhere("bet.status = 1");

        $params = array(
            "client" => $client,
            "draw" => $draw,
        );

        $queryBuilder->setParameters($params);
        return $queryBuilder->getQuery()->execute();
    }

}