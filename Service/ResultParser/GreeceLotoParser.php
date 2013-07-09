<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class GreeceLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.opap.gr/en/web/guest/lotto-draw-results';
     
     
     public function parse() {
         
         $file = $this->getHtmlPage();
         
         preg_match("/class=\"date\">[\D]+\s[\d\/]+/", $file, $rawDate);
         preg_match('/[\d\/]+/', $rawDate[0], $stringWords);
         $words = explode('/', $stringWords[0]);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         
         preg_match("/var s=\"[\d,]+/", $file, $ballsNodes);
         $ballsNodes = substr($ballsNodes[0], 7);
         $balls = explode(',', $ballsNodes);
         $bonus = array_pop($balls);
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             sort($balls);
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
         }
         return $this->hasResults();
     }
     
}
?>
