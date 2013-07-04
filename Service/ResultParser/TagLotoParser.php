<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class TagLotoParser extends AbstractLotoParser {
    
     private $templateUrl = 'http://www.lotterycanada.com/atlantic-tag';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDateDay = trim($crawler->filter('tr.odd td')->text());
         
         $rawDateYear = trim($crawler->filter('div.drawing dl dt')->text());
         $rawDateYear = substr($rawDateYear, 20);
         $rawDateDay = substr($rawDateDay, 0, 1);
         $rawDate = $rawDateDay.' '.$rawDateYear;
         $date = $this->getDate($rawDate);
         
         
         $ballsNodes = trim($crawler->filter('tr.odd td strong')->text());
         $ballsCnt = strlen($ballsNodes);
         $balls = array();
         
         for($i = 0; $i < $ballsCnt; $i++) {
             $balls[$i] = $ballsNodes[$i];
         }
         $this->validate($date);
         if($this->hasResult) {
         $result = $this->draw->getResult();
         $result->setResult($balls);
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
             $words = explode(' ', $rawDate);
             $day = $words[0];
             $month = $frMonth[strtolower($words[1])];
             $year = $words[2];
             
             $date = new \DateTime("$year-$month-$day");
             
             return $date;
        }
}
?>
