<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class TatsLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'http://tatts.com/tattersalls/results/tattslotto-latest-results?product=Tattslotto';

    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = $crawler->filter('span.resultHeadingDrawDateSpn')->text();
        $date = $this->getDate($rawDate);
        $drawNo=$this->getDrawNo($rawDate);
        $ballsNodes = $crawler->filter('span.resultNumberSpn');
        $ballsCnt = 6;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if ($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }

        $bonusNodes = $crawler->filter('span.resultSecondaryNumberColor');
        $bonusCnt = 2;
        $bonus = array();
        foreach ($bonusNodes as $bonusBall) {
            if($bonusCnt == 0)
                break;
            $bonus[] = trim($bonusBall->nodeValue);
            $bonusCnt--;
        }
        
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
        if ($this->hasResults()) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult($bonus);
            $this->draw->setIsParsed(1);  
            
        }
        return $this->hasResults();
    }

    private function getDate($rawDate)
    {
        $frMonth = array(
            'jun' => 1,
            'feb' => 2,
            'mar' => 3,
            'apr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'aug' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dec' => 12,
        );
        $rawDate = substr($rawDate, 22, 9);
        $words = explode("/", $rawDate);
        $day = $words[0];
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];

        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
    
     private function getDrawNo($rawNo)
    {
        $rawNo=  trim($rawNo);
        $rawNo=substr($rawNo, -5);
        $rawNo=  trim($rawNo);
       // print($rawNo."\n");
         return  $rawNo;
         
    }
}