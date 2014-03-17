<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Greece;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class GreeceLotoParser extends AbstractLotoParser {
     
     protected $templateUrl = 'http://www.opap.gr/en/web/guest/lotto-draw-results';
     
     public function parse() {
         
         $file = $this->getHtmlPage();
         
         preg_match("/class=\"date\">[\D]+\s[\d\/]+/", $file, $rawDate);
         preg_match('/[\d\/]+/', $rawDate[0], $stringWords);
         $words = explode('/', $stringWords[0]);
         $day = $words[0];
         $month = $words[1];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         print_r($date);
         preg_match("/class=\"number\">[\d\/]+/", $file, $rawN);
         
         preg_match('/[\d\/]+/', $rawN[0], $strRawN);
         $drawNo=trim($strRawN[0]);
         print($drawNo."\n");
         
         preg_match("/var s=\"[\d,]+/", $file, $ballsNodes);
         $ballsNodes = substr($ballsNodes[0], 7);
         $balls = explode(',', $ballsNodes);
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
             sort($balls);
             $result->setResult($balls);
             $result->setBonusResult(array($bonus));
             $this->draw->setLottoStatus(2);
         }
         return $this->hasResults();
     }
     
}
?>
