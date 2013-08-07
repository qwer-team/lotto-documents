<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MaximLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lottery.com.ua/pages/results/lotomx.php';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = $crawler->filter('tr td span')->eq(8)->text();
         $date = $this->getDate($rawDate);
         
         $i = 0;
         $ballsCnt = 4;
         $balls = array();
         while ($i <= $ballsCnt) {
             $balls[] = trim($crawler->filter("tr td div span#n$i")->text());
             $i++;
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
             'Января' => 1,
             'Февраля' => 2,
             'Марта' => 3,
             'Апреля' => 4,
             'Мая' => 5,
             'Июня' => 6,
             'Июля' => 7,
             'Августа' => 8,
             'Сентября' => 9,
             'Октября' => 10,
             'Ноября' => 11,
             'Декабря' => 12
         );
         preg_match('/\s([\d]+)\s([\D]+)\s([\d]+)/', $rawDate, $words);
         $day = $words[1];
         $month = $frMonth[strtolower($words[2])];
         $year = $words[3];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
?>
