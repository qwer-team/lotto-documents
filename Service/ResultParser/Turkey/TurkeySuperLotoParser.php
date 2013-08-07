<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Turkey;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class TurkeySuperLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lototurkiye.com/SUPER-LOTO/CEKILIS-SONUCLARI/4';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr td a.menucount h2')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td a img')->extract(array("alt"));
         $ballsCnt = 12;
         $ballsDouble = array();
         foreach($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             preg_match('/[\d]+/', $ball, $ballArray);
             $ballsDouble[] = $ballArray[0];
             $ballsCnt--;
         }
         $balls = array();
         $balls = array_unique($ballsDouble);
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
         }
         return $this->hasResults();
     }
     
     public function getDate($rawDate) {
         
         $frMonth = array(
             'Ocak' => 1,
             'Şubat' => 2,
             'Mart' => 3,
             'Nisan' => 4,
             'Mayıs' => 5,
             'Haziran' => 6,
             'Temmuz' => 7,
             'Ağustos' => 8,
             'Eylül' => 9,
             'Ekim' => 10,
             'Kasım' => 11,
             'Aralık' => 12
         );
         preg_match('/\s[\d]+\s[\D]+\s[\d]+/', $rawDate, $words);
         $word = explode(' ', trim($words[0]));
         $day = $word[0];
         $month = $frMonth[$word[1]];
         $year = $word[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
