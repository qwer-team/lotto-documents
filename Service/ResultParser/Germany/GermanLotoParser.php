<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Germany;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
 
 

class GermanLotoParser extends AbstractLotoParser {
    
    protected $templateUrl = 'http://www.lotto.de/bin/6aus49_archiv';
     
     public function parse() {
         
        $data = $this->getHtmlPage();
        $zzz= (array) json_decode($data,true);
        // print_r($zzz);
        $rawDate= $zzz[date("Y")][0]["date"];
      $date = $this->getDate( $rawDate);
      $drawNo=$date->format("Ymd");
         
         $balls=$zzz[$rawDate]["lotto"]["gewinnzahlen"];
         $bonus = $zzz[$rawDate]["lotto"]["superzahl"];
         //print_r( $balls); 
       
   
         
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
    
    
     
     $date = new \DateTime($rawDate);

     return $date;
    }
    
 
}
 