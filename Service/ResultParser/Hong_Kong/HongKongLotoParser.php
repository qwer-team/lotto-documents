<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Hong_Kong;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class HongKongLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://bet.hkjc.com/marksix/index.aspx?lang=en';
     
     public function parse() {
         
         $d= $this->draw->getDate()->format("d");
         $m= $this->draw->getDate()->format("m");
         $y= $this->draw->getDate()->format("Y");
         $this->templateUrl="http://bet.hkjc.com/marksix/Results_Detail.aspx?lang=en&date=".$d."/".$m."/".$y;
        $file = $this->getHtmlPage();
      //   print($file );
         
         /*
       
       
        preg_match('/Date : [\d\/]+/', $file, $rawDate);
       // print_r($rawDate);
        $rawDate = substr($rawDate[0], 7);
        $words = explode('/', $rawDate);
        $day = $words[0];
        $month = $words[1];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
     //   print_r($date);
        preg_match_all('/\/icon\/no_[\d]+/', $file, $ballsNodes);
        
        preg_match("/\>Draw Number : ".$date->format("y")."\/[\d]+/", $file, $rawN);
        $drawNo=trim($rawN[0] , ">Draw Number : ");
       
       // print($drawNo);
        $ballsCnt = 7;
        $balls = array();
        foreach($ballsNodes[0] as $ball) {
            if($ballsCnt == 0) 
                break;
            $balls[] = trim(substr($ball, 9));
            $ballsCnt--;
        }
   //     print_r($balls);
        $bonus = array_pop($balls);
      */ 
         
          $date = $this->draw->getDate();//$this->getDate($rawDate);
       //  $drawNo=$this->getDrawNo($date->format("Ymd"));
    
        
        preg_match_all('/\/icon\/no_[\d]+/', $file, $ballsNodes);
       // print_r($ballsNodes);
        preg_match("/\>Draw Number : ".$date->format("y")."\/[\d]+/", $file, $rawN);
        $drawNo=trim($rawN[0] , ">Draw Number : ");
       
      //  print($drawNo);
        $ballsCnt = 7;
        $balls = array();
        foreach($ballsNodes[0] as $ball) {
            if($ballsCnt == 0) 
                break;
            $balls[] = trim(substr($ball, 9));
            $ballsCnt--;
        }
      //  print_r($balls);
        $bonus = array_pop($balls);
         
        if(empty($balls)) {return 0;} 
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
    
    
    private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
?>
