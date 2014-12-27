<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Plus2LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://irish.national-lottery.com/';
     
     public function parse() {
         
          $d= $this->draw->getDate()->format("Y-m-d");
         $this->templateUrl="http://irish.national-lottery.com/results/irish-lotto-result-".$d.".asp";
         $crawler = $this->getCrawler();
         
    // $rawDate = trim($crawler->filter('tr th div.floatLeft')->text());
         $date = $this->draw->getDate();//$this->getDate($rawDate);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
        //print($drawNo."\n");
         
         $ballsNodes = $crawler->filter('.subgame .mediumResults td.irish-small-ball');
        // print_r($ballsNodes);
         
         $ballsCnt = 6;
         $i=0;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $i++;
             if($i>6){
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
             }
         }
         $bonusNodes = $crawler->filter('.subgame .mediumResults td.irish-small-bonus-ball');//->text();
        // print_r($bonus);
         $i=0;
         foreach ($bonusNodes as $ball) {
             $i++;
             if($i>1){
                 $bonus=trim($ball->nodeValue);
             }
         }
       //  print($bonus);
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
