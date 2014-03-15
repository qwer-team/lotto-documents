<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\France;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class JokerFranceLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'https://www.fdj.fr/jeux/jeux-de-tirage/jokerplus/resultats';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('h3.dateTirage')->text());
         $date = $this->getDate($rawDate);
         $i = 1;
         $ballsCnt = 7;
         $balls = array();
         while($i <= $ballsCnt) {
         $ballsNodes = $crawler->filterXPath("//*[@id=\"ajax-dest-calcul\"]/div[1]/div[2]/p[$i]")->text();
         $balls[] = $ballsNodes;
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
            'janvier' => 1,
            'février' => 2,
            'mars' => 3,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'août' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'décembre' => 12,
         );
         preg_match('/\s([\d]+)\s([\D]+)\s([\d]+)/', $rawDate, $words);
         $day = $words[1];
         $month = $frMonth[$words[2]];
         $year = $words[3];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
?>
