<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Plus1LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://irish.national-lottery.com/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr th div.floatLeft')->text());
         $date = $this->getDate($rawDate);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
         print($drawNo."\n");
         
         $ballsNodes = $crawler->filter('tr td.irish-small-ball');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $bonus = $crawler->filter('tr td.irish-small-bonus-ball')->text();
         
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
         if ($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
             $this->draw->setLottoStatus(2);
         }
         return $this->hasResults();
     }
     
     public function getDate($rawDate) {
         $words = explode('/', $rawDate);
         preg_match('/[\d]+/', $rawDate, $day);
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day[0]");
         return $date;
    }
    
     private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
