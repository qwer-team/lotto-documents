<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\European_Union;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class EuroLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.euro-millions.com/results.asp';
    
    public function parse() {
        
        $crawler = $this->getCrawler();
        
        $rawDate = $crawler->filter('tr th')->text();
        $rawDate = preg_replace('/^[\D]+/', '', $rawDate);
        $date = $this->getDate($rawDate);
        
        $ballsNodes = $crawler->filter('tr td.euro-ball');
        $ballsCnt = 5;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }
        
        $bonusNodes = $crawler->filter('tr td.euro-lucky-star');
        $bonusCnt = 2;
        $bonus = array();
        foreach ($bonusNodes as $bonusBall) {
            if($bonusCnt == 0)
                break;
            $bonus[] = trim($bonusBall->nodeValue);
            $bonusCnt--;
        }
        
        $this->validate($date);
        if($this->hasResult) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult($bonus);
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
        $day = preg_replace('/\D/', '', $words[0]);
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
        
        return $date;
    }
}
?>
