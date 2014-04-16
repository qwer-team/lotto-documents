<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\UK;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;


class UKLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'https://www.national-lottery.co.uk/player/lotto/results/results.ftl';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('td.first')->text());
         $date = $this->getDate($rawDate);
         $drawNo = trim($crawler->filterXpath('//*[@id="drawHistoryForm"]/table/tbody/tr[2]/td[6]')->text());
      // print($drawNo."\n");
         
         
         $ballsNodes = $crawler->filterXpath('//*[@id="drawHistoryForm"]/table/tbody/tr[2]/td[3]')->text();
         
         
         $ballsSpace = explode('-', $ballsNodes);
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsSpace as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball);
             $ballsCnt--;
         }
         
         $bonus = trim($crawler->filterXpath('//*[@id="drawHistoryForm"]/table/tbody/tr[2]/td[4]/span')->text());
         
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
             $rawDate = substr($rawDate, 4);
             $words = explode(' ', $rawDate);
             $day = $words[0];
             $month = $frMonth[strtolower($words[1])];
             $year = $words[2];
             $date = new \DateTime("$year-$month-$day");
             return $date;
         }
}
?>
