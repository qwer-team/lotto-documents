<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Russia;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Formula749LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.gosloto.ru/7x49/archive';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         
          $rawDate = trim($crawler->filter('ins.pseudo')->text());
         $date = $this->getDate($rawDate);
        
         
          $rawDrawNo = trim($crawler->filter('div.draw a')->text());
         $drawNo=$this->getDrawNo($rawDrawNo);
         
         $ballsNodes = $crawler->filter('div.numbers div b');
         $ballsCnt = 7;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if ($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
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
         if ($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $this->draw->setIsParsed(1); 
         }
         return $date;
     }
    
      public function getDate($rawDate) {
         $frMonth = array(
             'января' => 1,
             'февраля' => 2,
             'марта' => 3,
             'апреля' => 4,
             'мая' => 5,
             'июня' => 6,
             'июля' => 7,
             'августа' => 8,
             'сентября' => 9,
             'октября' => 10,
             'ноября' => 11,
             'декабря' => 12
         );
          $rawDate = trim($rawDate);
         $rawDate = substr( $rawDate, 0, -6);
         $words = explode('.', $rawDate);
         $day = $words[0];
         $month = $words[1]; //$frMonth[strtolower($words[1])];  
         $year=$words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
       private function getDrawNo($rawNo)
    {
       // $rawNo= str_replace("тиража №", "",$rawNo); 
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}
?>
