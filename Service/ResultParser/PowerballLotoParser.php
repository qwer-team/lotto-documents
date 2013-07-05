<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class PowerballLotoParser extends AbstractLotoParser {
    
     private $templateUrl = 'https://www.nationallottery.co.za/powerball_home/results.asp?type=1';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr td span.onGreenBackground')->text());
         $date = $this->getDate($rawDate);
         
         
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
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
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
}
?>
