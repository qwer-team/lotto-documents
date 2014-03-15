<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Canada;
 
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Goutte\Client;


class TagLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lotterycanada.com/atlantic-tag';
      
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDateDay = trim($crawler->filter('tr.odd td')->text());
         $rawDateDay = substr($rawDateDay, 0, 2);
         $rawDateDay = trim($rawDateDay);
         
         $rawDateYear = trim($crawler->filter('div.drawing dl dt')->text());
         $rawDateYear = trim($rawDateYear, "Winning Numbers for ");
         $rawDateYear = trim($rawDateYear);
         
         $rawDate = $rawDateDay.' '.$rawDateYear;
        // print($rawDate."\n");
         $date = $this->getDate($rawDate);
         $drawNo=$this->getDrawNo($rawDate);
         
         $ballsNodes = trim($crawler->filter('tr.odd td strong')->text());
         $ballsCnt = strlen($ballsNodes);
         $balls = array();
         
         
       
         
         for($i = 0; $i < $ballsCnt; $i++) {
             $balls[$i] = $ballsNodes[$i];
         }
         
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
        $this->resultAll->setUCor("parsing");
      
         $t->addLottoResultsAll( $this->resultAll);
       }
         $this->validate($date);
         if($this->hasResult) {
         $result = $this->draw->getResult();
         $result->setResult($balls);
         $this->draw->setLottoStatus(2); 
         }
         
         return $this->hasResults();
    }
         public function getDate($rawDate) {
             
             $frMonth = array(
                 'january' => 1,
                 'february' => 2,
                 'march' => 3,
                 'april' => 4,
                 'may' => 5,
                 'june' => 6,
                 'july' => 7,
                 'august' => 8,
                 'september' => 9,
                 'october' => 10,
                 'november' => 11,
                 'decembre' => 12
             );
             
             $rawDate = str_replace('  ', ' ', $rawDate);
             $rawDate = trim($rawDate);
             $words = explode(' ', $rawDate);
             $day = $words[0];
             $month = $frMonth[strtolower($words[1])];
             $year = $words[2];
             
             $date = new \DateTime("$year-$month-$day");
             
             return $date;
        }
        
          private function getDrawNo($rawNo)
    {
        $rawNo= str_replace(" ", "",$rawNo);
        $rawNo= str_replace(",", "",$rawNo);
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}

