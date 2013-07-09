<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class ElGordoLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.elgordo.com/results/maslottoen.asp';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr.tg th div.d_d')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td div.cuad');
         $ballsCnt = 5;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $bonus = trim($crawler->filter('tr td div.esp')->text());
         
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
         preg_match('/([\d]+)\W+([\D]+)[\W]\W+([\d]+)/', $rawDate, $words);
         
         $day = $words[1];
         $month = $frMonth[strtolower($words[2])];
         $year = $words[3];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
