<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MegaLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.msl.ua/?code=ml-results_archive';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('table.results_item tbody tr td')->text());
         $date = $this->getDate($rawDate);
         
         $balls = array();
         $ballsNodes = $crawler->filter('html body div.main_frame table.results_item tbody tr td p span')->text();
         $ball = str_replace('+ мегакулька:', ',', $ballsNodes);
         $ballsSpace = explode(',',  $ball);
         foreach ($ballsSpace as $ball) {
             $balls[] = trim($ball);
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
           
         $words = explode('.', $rawDate);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
    
}
?>
