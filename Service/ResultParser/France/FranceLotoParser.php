<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\France;

 
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class FranceLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'https://www.fdj.fr/jeux/jeux-de-tirage/loto/resultats';

    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = $crawler->filter('div.resultats-tirage h3.dateTirage')->text();
        $date = $this->getDate($rawDate);
        $drawNo=$this->getDrawNo($rawDate);
        
        $ballsNodes = $crawler->filter('div.resultats-tirage div.loto_numeros p.loto_boule');
        $ballsCnt = 5;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if ($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }

        $bonus = trim($crawler->filter('div.resultats-tirage div.loto_numeros p.loto_boule_c')->text());

        
         $t=$this->draw->getLottoTime()->getLottoType();
          if(!$this->repoResAll->findResultAllByTypeDrowNo($t,$drawNo)) {
            $drawTime=$this->draw->getLottoTime()->getTime();
            $h=$drawTime->format("H");
            $m=$drawTime->format("i");
            $date->setTime($h, $m);

            $this->resultAll->setLottoType($t);
            $this->resultAll->setDt($date);
            $this->resultAll->setDrawName($drawNo);
            $this->resultAll->setResult($balls); 
            $this->resultAll->setBonusResult(array($bonus));
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
         }
        
        $this->validate($date);
        if ($this->hasResults()) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult(array($bonus));
            $this->draw->setLottoStatus(2);
        }
        return $this->hasResults();
    }

    private function getDate($rawDate)
    {
        $frMonth = array(
            'janvier' => 1,
            'février' => 2,
            'mars' => 3,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'août' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'décembre' => 12,
        );
        $words = explode(" ", $rawDate);
        $day = $words[1];
        $month = $frMonth[strtolower($words[2])];
        $year = $words[3];

        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
    
     private function getDrawNo($rawNo)
    {
        $rawNo= str_replace("Mercredi", "",$rawNo);
        $rawNo= str_replace("Lundi", "",$rawNo);
        $rawNo= str_replace("Samedi", "",$rawNo);
        $rawNo= str_replace(" ", "",$rawNo);
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}