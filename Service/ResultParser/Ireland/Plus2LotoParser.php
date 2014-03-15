<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Plus2LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://irish.national-lottery.com/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr th div.floatLeft')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td.irish-small-ball');
         $ballsCnt = 12;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $balls = array_slice($balls, 6);
         
         $bonusNodes = $crawler->filter('tr td.irish-small-bonus-ball');
         $bonusCnt = 2;
         $bonus = array();
         foreach ($bonusNodes as $ball) {
             if($bonusCnt == 0)
                 break;
             $bonus[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus[1]));
         }
     }
     
     public function getDate($rawDate) {
         
         $words = explode('/', $rawDate);
         preg_match('/[\d]+/', $rawDate, $day);
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day[0]");
         return $date;
     }
}
?>
