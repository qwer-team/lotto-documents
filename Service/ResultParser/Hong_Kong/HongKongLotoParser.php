<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Hong_Kong;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class HongKongLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://bet.hkjc.com/marksix/index.aspx?lang=en';
     
     public function parse() {
         
        $file = $this->getHtmlPage();
      //  print(strlen($file)."\n" );
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
}
?>
