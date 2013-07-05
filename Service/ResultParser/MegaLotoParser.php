<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MegaLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.msl.ua/?code=ml';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('span.ml_title span')->text());
         $date = $this->getDate($rawDate);
         
     }
     
     public function getDate($rawDate) {
         
         $frMonth = array(
             'січня' => 1,
             'лютого' => 2,
             'березня' => 3,
             'квітня' => 4,
             'травня' => 5,
             'червня' => 6,
             'липня' => 7,
             'серпня' => 8,
             'вересня' => 9,
             'жовтня' => 10,
             'листопада' => 11,
             'грудня' => 12
         );
         /*$tab = array( "CP1251");
         foreach($tab as $set){
             echo $set."\n";
             echo iconv($set, "UTF-8", $rawDate);
             echo "\n";
         }*/
      //echo $rawDate;
         $rawDate = substr($rawDate, 13);
         $words = explode(' ', $rawDate);
         $month = $frMonth[$words[1]];
         //print_r($words);
     }
    
}
?>
