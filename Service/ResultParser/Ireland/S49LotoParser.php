<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class S49LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.49s.co.uk/49s/LatestResults.aspx';
     
     public function parse() {
          if($this->draw->getLottoTime()->getTime()->format("H")=="14" ) {
              $drawTime="LT";              
          } else {
              $drawTime="TT"; 
          }
          
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('h2#ctl00_BodyContent_results_title_text')->text());
         $date = $this->getDate($rawDate);
          $drawNo=$this->getDrawNo($date->format("Ymd").$drawTime); 
         
          if($drawTime=="LT") {
                $ballsNodes = $crawler->filter('div#ctl00_BodyContent_main_drawLunch.main_draw img')->extract(array('alt'));
                $bonus = $crawler->filter('div#BoosterBallLunch.booster_ball img')->extract(array('alt'));
                
          } else {
              $ballsNodes = $crawler->filter('div#ctl00_BodyContent_main_drawTea.main_draw img')->extract(array('alt'));
              $bonus = $crawler->filter('div#BoosterBallTea.booster_ball img')->extract(array('alt'));
              
          }
         $balls= array();
         
                $ballsCnt = 6;
                foreach($ballsNodes as $ball) {
                   if($ballsCnt == 0)
                       $break;
                   $balls[] = $ball;
                   $ballsCnt--;
                }
           
         //      print_r($balls);
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
            $this->resultAll->setBonusResult($bonus);
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
             
         }
      
          
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult($bonus);
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
             'decembre' => 12
         );
         
         preg_match('/\s[\d]+\s[\D]+/', $rawDate, $words);
         $word = explode(' ', trim($words[0]));
         $day = $word[0];
         $month = $frMonth[strtolower($word[1])];
         $year = date("Y");
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
     private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
?>
