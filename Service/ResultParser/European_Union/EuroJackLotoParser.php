<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\European_Union;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class EuroJackLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.euro-millions.com/eurojackpot-results.asp';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr th')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td.jack-ball');
         $ballsCnt = 5;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if ($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $bonusNodes = $crawler->filter('tr td.jack-euro');
         $bonusCnt = 2;
         $bonus = array();
         foreach ($bonusNodes as $ball) {
             if($bonusCnt == 0)
                 break;
             $bonus[] = trim($ball->nodeValue);
             $bonusCnt--;
         }
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult($bonus);
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
         preg_match('/\s[\D]+\s[\d]+[\D]+\s[\d]+/', $rawDate, $words);
         $words = explode(' ', trim($words[0]));
         preg_match('/[\d]+/', $words[1], $day);
         $month = $frMonth[strtolower($words[0])];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day[0]");
         return $date;
     }
}
?>
