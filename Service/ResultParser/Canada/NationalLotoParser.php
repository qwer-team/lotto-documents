<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Canada;

use Goutte\Client;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;


class NationalLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = "http://www.lotterycanada.com/lotto-649";
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = $crawler->filter('div.drawing dl dt')->text();
        // print($rawDate);
         $date = $this->getDate($rawDate);
        
         $drawNo=$this->getDrawNo($rawDate);
         $ballsNodes = $crawler->filter('span.regNums span');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         
         $bonus = trim($crawler->filter('span.bonus span')->text());
         
         
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
         
     //   print_r($date);
       // print_r($this->draw->getDate());
         $this->validate($date);
      //   print($this->hasResult." ----- ");
         
         if($this->hasResult) {
      //       print("da");
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
             $this->draw->setIsParsed(1);  
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
             'december' => 12
         );
         
         
     //    print_r($rawDate);
        // $rawDate = substr($rawDate, 5, 13);
         $rawDate = str_replace(',', '', $rawDate);
         $rawDate = str_replace('  ', ' ', $rawDate);
         $rawDate = trim($rawDate);
         $words = explode(" ", $rawDate);
         
         $day = $words[1];
         $month = $frMonth[strtolower($words[0])];
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
