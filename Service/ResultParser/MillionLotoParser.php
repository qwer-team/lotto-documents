<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MillionLotoParser extends AbstractLotoParser {
    
     private $templateUrl = 'http://nylottery.ny.gov/wps/portal/!ut/p/c5/04_SB8K8xLLM9MSSzPy8xBz9CP0os_jggBC3kDBPE0MLC0dnA09vT0fLQDNvA0dfU30_j_zcVP1I_ShzXKoCgw30I3NS0xOTK_ULst0cAYmfjdU!/dl3/d3/L0lJSklna21BL0lKakFBRXlBQkVSQ0pBISEvNEZHZ3NvMFZ2emE5SUFnIS83X1NQVEZUVkk0MTg4QUMwSUtJQTlRNkswUVMwLzc2ZU1zMTQyNjAwNzM!/?PC_7_SPTFTVI4188AC0IKIA9Q6K0QS0_WCM_CONTEXT=/wps/wcm/connect/NYSL+Content+Library/NYSL+Internet+Site/Home/Jackpot+Games/SWEET+MILLION/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('div.WinningNumbersText')->text());
         $words = explode('/', $rawDate);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         
         $ballsNodes = $crawler->filter('tr td div.WinningNumbersResultsSweetMillion');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
         }
         return $this->hasResults();
     }
}
?>
