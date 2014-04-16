<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\South_Africa;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class PowerballLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'https://www.nationallottery.co.za/powerball_home/results.asp?type=1';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr td span.onGreenBackground')->text());
         $date = $this->getDate($rawDate);
      //   print_r($date);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
         
         $ballsNodes = $crawler->filter('tr td.bbottomYellow div img')->extract(array('src'));
         $ballsNodes = array_unique($ballsNodes);
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             preg_match('/[\d]+/', $ball, $ballNum);
             $balls[] = $ballNum[0];
             $ballsCnt--;
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
         $rawDate = preg_replace('/[\D]+,\s/', '', $rawDate);
         $words = explode(' ', $rawDate);
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
?>
