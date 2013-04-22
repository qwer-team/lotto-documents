<?php

namespace Qwer\LottoDocumentsBundle\Repository;

use Itc\DocumentsBundle\Entity\Document\RestRepository as ParentRepository;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoBundle\Entity\Draw;

class RestRepository extends ParentRepository
{

    public function findByAccountAndLevels($accountId, $levels)
    {

        $qb = $this->createQueryBuilder("R");

        $qb->select("R")
        ->where("R.accountId = :accountId")
        ->setParameter("accountId", $accountId);

        for ($level = 1; $level <= 4; $level++) {
            $levelString = "level{$level}";
            $value = $levels[$levelString];
            if (is_null($value)) {
                continue;
            }
            $qb->andWhere("R.{$levelString} = :{$levelString}")
            ->setParameter($levelString, $value);
        }
        ;

        return $qb->getQuery()->execute();
    }

    public function getCumulativeAmount(Client $client, $externalId, Draw $draw, $ballsString)
    {
        $clientId = $client->getId();
        $drawId = $draw->getId();

        $qb = $this->createQueryBuilder("rest");

        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString("1 month"));
        $year = $date->format("y");
        $month = $date->format("m");

        $qb->select("rest.sd");

        $qb->where("rest.level1 = :level1");
        $qb->andWhere("rest.level2 = :level2");
        $qb->andWhere("rest.level3 = :level3");
        $qb->andWhere("rest.level4 = :level4");
        $qb->andWhere("rest.year = :year");
        $qb->andWhere("rest.month = :month");

        $params = array(
            "level1" => $clientId,
            "level2" => $externalId,
            "level3" => $drawId,
            "level4" => $ballsString,
            "year" => $year,
            "month" => $month,
        );

        $qb->setParameters($params);

        $result = $qb->getQuery()->getOneOrNullResult();

        if (is_null($result)) {
            $result = 0;
        }

        return $result["sd"];
    }

}