<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class OzLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'http://tatts.com/tattersalls/results/oz-lotto-latest-results?product=Super7sOzLotto';

    public function parse()
    {
        $crawler = $this->getCrawler();
        
        $arrNode=$crawler->filter('.lotto-draw-result .draw-date')->each(function ($node, $i)
        { return trim(trim($node->nodeValue),",") ;

        });
  
        $d= $this->draw->getDate()->format("D d/M/y");
     
        $key = array_search($d , $arrNode); 
         if($key === false) {
            return 0;
        } 
     
        $rawDate = trim($crawler->filter('.lotto-draw-result .draw-date')->eq($key)->text());
     
        $date = $this->draw->getDate();
   
       
        $drawNo=$this->getDrawNo( trim($crawler->filter('.lotto-draw-result .draw-number')->eq($key)->text()));
 
        
        $ballsCnt = 7;
        $balls = array();
 
        
        for ($i = 0; $i <= 6; $i++) {
            $balls[]=trim($crawler->filter('.lotto-draw-result .draw-details .primary .drawn-number')->eq($key*7+$i)->text());
        }
     
      //  $bonusCnt = 2;
        $bonus = array();
 
        for ($i = 0; $i <= 1; $i++) {
            $bonus[]=trim($crawler->filter('.lotto-draw-result .draw-details .secondary .drawn-number')->eq($key*7+$i)->text());
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

     
    
     private function getDrawNo($rawNo)
    {
      $rawNo=  trim($rawNo, "Draw ");
 
         return  $rawNo;
         
    }
    
}