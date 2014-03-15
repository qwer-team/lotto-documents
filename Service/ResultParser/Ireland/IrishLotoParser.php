<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class IrishLotoParser extends AbstractLotoParser 
{
 
     protected $templateUrl = 'http://irish.national-lottery.com/results/irish-lotto.asp';
    
    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = trim($crawler->filter('div.drawtitle a')->text());
        $date = $this->getDate($rawDate);
        $drawNo=$this->getDrawNo($rawDate);
        
        $ballsNodes = $crawler->filter('td.irish-ball');
        $ballsCnt = 6;
        $balls = array();
        foreach ($ballsNodes as $ball) 
        {
            if($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }
        $bonus = trim($crawler->filter('td.irish-bonus-ball')->text());
        
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
            $this->draw->setLottoStatus(2);
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
        $rawNo= str_replace(" ", "",$rawNo);
        $rawNo= str_replace("/", "",$rawNo);
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}
