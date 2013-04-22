<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Time;
use Itc\DocumentsBundle\Listener\ContainerAware;
use Doctrine\ORM\EntityManager;

class DrawFinder extends ContainerAware
{

    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Time $type
     * @param integer $drawNum
     */
    public function getDraws(Time $type, $drawNum)
    {
        $draws = $this->getDrawsRepo()->findNextDraws($type, $drawNum);

        return $draws;
    }

    /**
     * 
     * @return \Qwer\LottoBundle\Repository\DrawRepository
     */
    private function getDrawsRepo()
    {
        $repo = $this->em->getRepository("QwerLottoBundle:Draw");
        
        return $repo;
    }

    public function setEntityManager(EntityManager $manager)
    {
        $this->em = $manager;
    }

}