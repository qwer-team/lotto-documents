<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MegaLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.msl.ua/?code=ml-results_archive';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('table.results_item tbody tr td')->text());
         $date = $this->getDate($rawDate);
         //print_r($date);
         $rawDrawNo = trim($crawler->filter('a.results_a')->text());
         $drawNo=$this->getDrawNo($rawDrawNo);
        // print($drawNo."\n");
         $balls = array();
         $ballsNodes = $crawler->filter('html body div.main_frame table.results_item tbody tr td p span')->text();
         $ball = str_replace('+ мегакулька:', ',', $ballsNodes);
         $ballsSpace = explode(',',  $ball);
         foreach ($ballsSpace as $ball) {
             $balls[] = trim($ball);
         }
         $bonus = array_pop($balls);
         
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
           
         $words = explode('.', $rawDate);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
      private function getDrawNo($rawNo)
    {
        $rawNo= str_replace("Тираж", "",$rawNo); 
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
    
}
?>
