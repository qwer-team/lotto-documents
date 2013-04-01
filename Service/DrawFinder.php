<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Time;
use Doctrine\Common\Collections\ArrayCollection;
use Itc\DocumentsBundle\Listener\ContainerAware;

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
     * @return 
     */
    private function getDrawsRepo()
    {
        $repo = $this->em->getRepository("QwerLottoBundle:Draw");
        
        return $repo;
    }

    public function setEntityManager($manager)
    {
        $this->em = $manager;
    }

}