<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class S49LotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.49s.co.uk/49s/LatestResults.aspx';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('h2#ctl00_BodyContent_results_title_text')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodesLT = $crawler->filter('div#ctl00_BodyContent_main_drawLunch.main_draw img')->extract(array('alt'));
         $ballsLunchTime = array();
         $ballsCnt = 6;
         foreach($ballsNodesLT as $ball) {
            if($ballsCnt == 0)
                $break;
            $ballsLunchTime[] = $ball;
            $ballsCnt--;
         }
         $bonusLunchTime = $crawler->filter('div#BoosterBallLunch.booster_ball img')->extract(array('alt'));
         
         $ballsNodesTT = $crawler->filter('div#ctl00_BodyContent_main_drawTea.main_draw img')->extract(array('alt'));
         $ballsTeaTime = array();
         $ballsCnt = 6;
         foreach ($ballsNodesTT as $ball) {
             if($ballsCnt == 0)
                 break;
             $ballsTeaTime[] = $ball;
             $ballsCnt--;
         }
         $bonusTeaTime = $crawler->filter('div#BoosterBallTea.booster_ball img')->extract(array('alt'));
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($ballsLunchTime);
             $result->setBonusResult($bonusLunchTime);
             $result->setSecondResult($ballsTeaTime);
             $result->setSecondBonusResult($bonusTeaTime);
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
         
         preg_match('/\s[\d]+\s[\D]+/', $rawDate, $words);
         $word = explode(' ', trim($words[0]));
         $day = $word[0];
         $month = $frMonth[strtolower($word[1])];
         $year = date("Y");
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
}
?>
