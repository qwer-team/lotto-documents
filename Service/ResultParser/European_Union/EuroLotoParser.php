<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\European_Union;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class EuroLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.euro-millions.com/results.asp';
    
    public function parse() {
        
        $crawler = $this->getCrawler();
        
        $rawDate = $crawler->filter('div.date')->text();
       
        $date = $this->getDate($rawDate);
        $drawNo=$this->getDrawNo($date->format("Ymd"));
        
        $ballsNodes = $crawler->filter('li.ball');
        $ballsCnt = 5;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }
     //   print_r($balls);
        $bonusNodes = $crawler->filter('li.lucky-star');
        $bonusCnt = 2;
        $bonus = array();
        foreach ($bonusNodes as $bonusBall) {
            if($bonusCnt == 0)
                break;
            $bonus[] = trim($bonusBall->nodeValue);
            $bonusCnt--;
        }
    //    print_r($bonus);
        
        $t=$this->draw->getLottoTime()->getLottoType();
          if(!$this->repoResAll->findResultAllByTypeDrowNo($t,$drawNo)) {
            $drawTime=$this->draw->getLottoTime()->getTime();
            $h=$drawTime->format("H");
            $m=$drawTime->format("i");
            $date->setTime($h, $m);

            $this->resultAll->setLottoType($t);
            $this->resultAll->setDt($date);
            $this->resultAll->setDrawName($drawNo);
            $this->resultAll->setResult($balls); 
            $this->resultAll->setBonusResult($bonus);
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
         }
        
        $this->validate($date);
        if($this->hasResult) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult($bonus);
            $this->draw->setLottoStatus(2);
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
        $rawDate = str_replace(array('Friday','Tuesday','st','rd','nd','th'), '', $rawDate);
        $rawDate =trim($rawDate);
      //  print_r($rawDate);
        $words = explode(' ', $rawDate);
        $day =$words[0]; // preg_replace('/\D/', '', $words[0]);
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
        
        return $date;
    }
    
    private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
?>
