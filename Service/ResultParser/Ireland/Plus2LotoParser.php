<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Plus2LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://irish.national-lottery.com/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr th div.floatLeft')->text());
         $date = $this->getDate($rawDate);
          $drawNo=$this->getDrawNo($date->format("Ymd"));
         
         $ballsNodes = $crawler->filter('tr td.irish-small-ball');
         $ballsCnt = 12;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $balls = array_slice($balls, 6);
         
         $bonusNodes = $crawler->filter('tr td.irish-small-bonus-ball');
         $bonusCnt = 2;
         $bonus = array();
         foreach ($bonusNodes as $ball) {
             if($bonusCnt == 0)
                 break;
             $bonus[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
        // print_r($balls);
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
            $this->resultAll->setBonusResult(array($bonus[1]));
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
         }
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus[1]));
              $this->draw->setIsParsed(1); 
         }
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
?>
