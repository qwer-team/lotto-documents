<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Russia;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class Formula749LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.gosloto.ru/7x49/archive';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr td.date')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td.numbers ul li');
         $ballsCnt = 7;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if ($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         
         $this->validate($date);
         if ($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
         }
         return $date;
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
