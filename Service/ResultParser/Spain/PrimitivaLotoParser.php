<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Spain;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class PrimitivaLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.primitivalottery.com/Spanish-Lotto-Results.php';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('h2 strong')->text());
         $date = $this->getDate($rawDate);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
        // print($drawNo."\n");
         
         $ballsNodes = trim($crawler->filter('div.txt14')->text());
         //print($ballsNodes);
         if($ballsNodes=="Results coming soon") {return $this->hasResults();}
         
         $balls = explode(', ', $ballsNodes);
       //  print_r($balls);
         
         $bonus = trim($crawler->filterXpath('//*[@id="column2"]/div/div/div[1]/div[2]')->text());
         
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
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
             $this->draw->setLottoStatus(2);
         }
         return $this->hasResults();
     }
     
     public function getDate($rawDate) {
         
         $frMonth = array(
             'jan' => 1,
             'feb' => 2,
             'mar' => 3,
             'apr' => 4,
             'may' => 5,
             'jun' => 6,
             'jul' => 7,
             'aug' => 8,
             'sep' => 9,
             'oct' => 10,
             'nov' => 11,
             'dec' => 12
         );
         preg_match('/,\s[\D]+\s[\d]+,\s[\d]+/', $rawDate, $date);
         $date = str_replace(',', '', $date);
         $words = explode(' ', trim($date[0]));
         $day = $words[1];
         $month = $frMonth[strtolower($words[0])];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
      private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
     
}
