<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\European_Union;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class EuroJackLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.euro-millions.com/eurojackpot-results.asp';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = $crawler->filter('div.date')->text();
         $date = $this->getDate($rawDate);
          $drawNo=$this->getDrawNo($date->format("Ymd"));
          
         $ballsNodes = $crawler->filter('li.jack-ball');
         $ballsCnt = 5;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if ($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         
         $bonusNodes = $crawler->filter('li.jack-euro');
         $bonusCnt = 2;
         $bonus = array();
         foreach ($bonusNodes as $ball) {
             if($bonusCnt == 0)
                 break;
             $bonus[] = trim($ball->nodeValue);
             $bonusCnt--;
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
             'december' => 12
         );
         preg_match('/\s[\D]+\s[\d]+[\D]+\s[\d]+/', $rawDate, $words);
         $words = explode(' ', trim($words[0]));
         preg_match('/[\d]+/', $words[1], $day);
         $month = $frMonth[strtolower($words[0])];
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
