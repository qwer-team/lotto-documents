<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Germany;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class GermanLotoParser extends AbstractLotoParser {
    
    // protected $templateUrl = 'http://www.lotto.de/de/ergebnisse/6aus49_results/archiv/results_6aus49.xhtml';
     protected $templateUrl = 'http://www.lotto.net/ru/rezultaty-nemeckogo-loto.asp';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
        // $rawDate = $crawler->filterXpath('//*[@id="j_id_3:j_id_4:tag"]/option[1]')->text();
        $rawDate = trim($crawler->filter('tr,headRow th')->text());
        
        
         $date = $this->getDate($rawDate);
       //  print_r($date); 
         $drawNo=$this->getDrawNo($date->format("Ymd"));
        // $i = 1;
        // $Xpath = "//*[@id=\"j_id_3:j_id_4\"]/div[2]/ul[1]/li['$i']";
         //$ballsNodes = $crawler->filterXpath($Xpath);
          $ballsNodes = $crawler->filter('td.resultBall');
      //    print_r($ballsNodes); 
         $ballsCnt = 7;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0) 
                 break;
             $balls[] = $ball->nodeValue;
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
             $this->draw->setLottoStatus(2);
         }
         return $this->hasResults();
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
     $rawDate= str_replace("среда", "",$rawDate);
     $rawDate= str_replace("суббота", "",$rawDate);
     $rawDate= str_replace("года,", "",$rawDate);
     $rawDate= str_replace("  ", " ",$rawDate);
     $rawDate=trim($rawDate);
     $words = explode(" ", $rawDate);
     $day = $words[0];
     $month = $frMonth[strtolower($words[1])];
     $year = $words[2];
     
     $date = new \DateTime("$year-$month-$day");

     return $date;
    }
    
    private function getDrawNo($rawNo)
    {
        //$rawNo= substr($rawNo, 10);
        //$rawNo= str_replace("-", "",$rawNo);
       // $rawNo = trim($rawNo);
         return trim($rawNo);
         
    }
}
?>
