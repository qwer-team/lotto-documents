<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Germany;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class GermanLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lotto.de/de/ergebnisse/6aus49_results/archiv/results_6aus49.xhtml';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = $crawler->filterXpath('//*[@id="j_id_3:j_id_4:tag"]/option[1]')->text();
         $date = $this->getDate($rawDate);
         $i = 1;
         $Xpath = "//*[@id=\"j_id_3:j_id_4\"]/div[2]/ul[1]/li['$i']";
         $ballsNodes = $crawler->filterXpath($Xpath);
         $ballsCnt = 7;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0) 
                 break;
             $balls[] = $ball->nodeValue;
             $ballsCnt--;
         }
         $bonus = array_pop($balls);
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
         }
         return $this->hasResults();
    }
    public function getDate($rawDate) {
        
     preg_match('/[\d\.]+/', $rawDate, $words);
     $words = explode('.', $words[0]);
     $day = $words[0];
     $month = $words[1];
     $year = $words[2];
     
     $date = new \DateTime("$year-$month-$day");

     return $date;
    }
}
?>
