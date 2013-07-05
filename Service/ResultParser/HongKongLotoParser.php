<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class HongKongLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://bet.hkjc.com/marksix/index.aspx?lang=en';
     
     public function parse() {
         
        $file = $this->getHtmlPage();
        
        preg_match('/Date : [\d\/]+/', $file, $rawDate);
        $rawDate = substr($rawDate[0], 7);
        $words = explode('/', $rawDate);
        $day = $words[0];
        $month = $words[1];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
        
        preg_match_all('/\/icon\/no_[\d]+/', $file, $ballsNodes);
        
        $ballsCnt = 7;
        $balls = array();
        foreach($ballsNodes[0] as $ball) {
            if($ballsCnt == 0) 
                break;
            $balls[] = trim(substr($ball, 9));
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
