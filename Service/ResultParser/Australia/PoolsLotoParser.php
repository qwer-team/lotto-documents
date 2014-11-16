<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Goutte\Client;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;

class PoolsLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://tatts.com/tattersalls/results/last-10-results?product=ThePools';


     public function parse () {
         
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
       
        $balls = array();
 
        for ($i = 0; $i <= 5; $i++) {
            $balls[]=trim($crawler->filter('.lotto-draw-result .draw-details .primary .drawn-number')->eq($key*6+$i)->text());
        }
 
        $bonus=trim($crawler->filter('.lotto-draw-result .draw-details .secondary .drawn-number')->eq($key)->text());
        
         
         
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
         
     }
      
         
      private function getDrawNo($rawNo)
    {
      $rawNo=  trim($rawNo, "Draw ");
 
         return  $rawNo;
         
    }   
         
}

 