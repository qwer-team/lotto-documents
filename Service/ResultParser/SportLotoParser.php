<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class SportLotoParser extends AbstractLotoParser {
    
     private $templateUrl = 'http://www.sportloto6x49.ru/archive';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         
         $rawDate = trim($crawler->filterXpath('//*[@id="content"]/table/tbody/tr[2]/td[2]')->text());
         $words = explode('.', $rawDate);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         
         $ballsNodes = $crawler->filter('td.numbers ul li');
         $ballsCnt = 7;
         $balls = array();
         foreach($ballsNodes as $ball) {
             if($ballsCnt == 0) 
                 break;
             $balls[] = trim($ball->nodeValue);
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
}
?>
