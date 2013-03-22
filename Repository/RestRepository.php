<?php

namespace Qwer\LottoDocumentsBundle\Repository;

use Itc\DocumentsBundle\Entity\Document\RestRepository as ParentRepository;

class RestRepository extends ParentRepository
{
    public function findByAccountAndLevels($accountId, $levels)
    {

        $qb = $this->createQueryBuilder("R");

        $qb->select("R")
           ->where("R.accountId = :accountId")
           ->setParameter("accountId", $accountId);

        for ($level = 1; $level != 3; $level++) {
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
}