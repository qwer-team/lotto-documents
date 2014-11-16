<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class MegaLotoParser extends AbstractLotoParser {
    
     //protected $templateUrl = 'http://www.msl.ua/?code=ml-results_archive';
     protected $templateUrl = '';
    protected  $frMonth = array(
            1 =>  'січня' , 
            2 =>  'лютого' , 
            3 =>  'березня' , 
            4 =>  'квітня' , 
            5 =>  'травня' , 
            6 =>  'червня' , 
            7 =>  'липня' , 
            8 =>  'серпня' , 
            9 =>  'вересня' , 
            10 =>  'жовтня' , 
            11 =>  'листопада' , 
            12 =>  'грудня' 
         );  
     
public function parse() {
      
$crawler = $this->getCrawler();
           
$arrNode=$crawler->filter('.mb20 .span2')->each(function ($node, $i)
{ return $node->nodeValue ;

});
//print_r($arrNode);
$d= $this->draw->getDate()->format("j");
$m= $this->frMonth[$this->draw->getDate()->format("n")];
$y= $this->draw->getDate()->format("Y");

//print($d." ".$m." ".$y);
      // 5 листопада 2014
$key = array_search($d." ".$m." ".$y, $arrNode);      
 if($key === false) {
            return 0;
        } 
         $crawler = $this->getCrawler();
         
         $rawDate = trim($crawler->filter('.mb20 .span2')->eq($key)->text());
         $date = $this->getDate($rawDate);
      //   print_r($date);
         $rawDrawNo = trim($crawler->filter('.mb20 .span10 a')->eq($key)->text());
         $drawNo=$this->getDrawNo($rawDrawNo);
       //  print($drawNo."\n");
         $balls = array();
         
         $ballsNodes = $crawler->filter('.mb20 .span10 a + div')->eq($key)->text();
          $ballsNodes = trim(str_replace('Результати', '', $ballsNodes));
       //   print($ballsNodes."\n");
         $ball = str_replace('+ мегакулька:', ',', $ballsNodes);
    //     print($ball."\n");
         $ballsSpace = explode(',',  $ball);
         foreach ($ballsSpace as $ball) {
             $balls[] = trim($ball);
         }
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
     
     public function getDate($rawDate) {
           
         $words = explode(' ', $rawDate);
         $day = $words[0];
         
         $ar_m=array_keys($this->frMonth, $words[1]);
         
         $month = $ar_m[0];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
      private function getDrawNo($rawNo)
    {
        $rawNo= str_replace("Тираж", "",$rawNo); 
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
    
}
?>
