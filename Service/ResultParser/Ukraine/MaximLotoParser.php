<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MaximLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://lottery.com.ua/ru/lottery/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
          $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('span #MAXIMA_RESULT_DATE')->text());
        // print($rawDate."\n");
         $date = $this->getDate($rawDate);
         $rawDrawNo = trim($crawler->filter('span #MAXIMA_RESULT_DRAW')->text());
         $drawNo=$this->getDrawNo($rawDrawNo);
         
         $ballsCnt = 5;
         $balls = array();
         $ballsNodes = $crawler->filter('div #MAXIMA_RESULT_BALLS div');
         
         
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0) 
                 break;
             $balls[] =  $rawNo= str_replace("small_ball c", "", trim($ball->getAttribute('class'))); 
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
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $this->draw->setIsParsed(1); 
         }
         return $this->hasResults();
     }
     public function getDate($rawDate) {
          $words = explode('.', $rawDate);
	 $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
      private function getDrawNo($rawNo)
    {
        $rawNo= str_replace("№", "",$rawNo); 
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}
?>
