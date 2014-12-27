<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class IrishLotoParser extends AbstractLotoParser 
{
 
     protected $templateUrl = 'http://irish.national-lottery.com/results/irish-lotto.asp';
    
    public function parse()
    {
           $d= $this->draw->getDate()->format("Y-m-d");
         $this->templateUrl="http://irish.national-lottery.com/results/irish-lotto-result-".$d.".asp";
         $crawler = $this->getCrawler();
         
    // $rawDate = trim($crawler->filter('tr th div.floatLeft')->text());
         $date = $this->draw->getDate();//$this->getDate($rawDate);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
        //print($drawNo."\n");
         
         $ballsNodes = $crawler->filter('.irish-ball');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $bonus = $crawler->filter('.irish-bonus-ball')->text();
        
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
            $this->resultAll->setBonusResult(array($bonus));
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
         }
        
        $this->validate($date);
        if($this->hasResult) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult(array($bonus));
            $this->draw->setIsParsed(1); 
        }
        return $this->hasResults();
    }
    
    public function getDate($rawDate) {
        $rawDate = trim($rawDate);
        $rawDate = substr($rawDate,-10);
        //print($rawDate);
        $words = explode('/', $rawDate);
   // print_r($words);
        $day = $words[0];
        $month = $words[1];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
    
     private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
