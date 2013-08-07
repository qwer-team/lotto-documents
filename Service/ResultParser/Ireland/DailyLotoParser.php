<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class DailyLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://irish.national-lottery.com/results/daily-million.asp';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('span.drawtitle')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td.daily-mill-ball');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = $ball->nodeValue;
             $ballsCnt--;
         }
         
         $bonus = trim($crawler->filter('tr td.daily-mill-bonus-ball')->text());
         
         $this->validate($date);
         if ($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
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
         preg_match('/([\d]+)[\D]+\s([\D]+)\s([\d]+)/', $rawDate, $words);
         $day = $words[1];
         $month = $frMonth[strtolower($words[2])];
         $year = $words[3];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
?>
