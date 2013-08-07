<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Goutte\Client;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;

class PoolsLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://tatts.com/tattersalls/results/last-10-results?product=ThePools';


     public function parse () {
         
         $crawler = $this->getCrawler();
         $rawDate = $crawler->filter('span.resultHeadingDrawDateSpn')->text();
         
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('span.resultNumberSpn');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if ($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;             
         }
         
         $bonus = trim($crawler->filter('span.resultSecondaryNumberColor')->text());
         
         
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
         }
         
     }
     private function getDate($rawDate) {
         
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
         
         $rawDate = substr($rawDate, 21, 9);
         $words = explode("/", $rawDate);
         $day = $words['0'];
         $month = $frMonth[strtolower($words[1])];
         $year = $words['2'];
         $date = new \DateTime("$year-$month-$day");
         return $date;
         }
}

?>
