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
    
    public function getClientsBetsOnDraw($client, $draw )
    {
        $queryBuilder = $this->createQueryBuilder("bet");

        $queryBuilder->select("bet")
                ->where("bet.lottoClient = :client")
                ->andWhere("bet.lottoDraw = :draw") ;
                
        $params = array(
            "client" => $client,
            "draw" => $draw, 
        );

        $queryBuilder->setParameters($params);
        return $queryBuilder->getQuery()->execute();
    }
    
    public function getBetsByIds($ids){
        $queryBuilder = $this->createQueryBuilder("bet");

        $queryBuilder->select("bet, draw, time, type, country")
                ->innerJoin("bet.lottoDraw", "draw")
                ->innerJoin("draw.lottoTime", "time")
                ->innerJoin("time.lottoType", "type")
                ->innerJoin("type.country", "country")
                ->where("bet.id in (:ids)");
        $queryBuilder->setParameter("ids", $ids);
        return $queryBuilder->getQuery()->execute();
    }

}