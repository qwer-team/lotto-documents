<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Goutte\Client;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;

class Super66LotoParser extends AbstractLotoParser {
    
    // protected $templateUrl = 'http://tatts.com/tattersalls/results/last-10-results?product=Super66';
 protected $templateUrl = '';
 
    public function parse() {
       
        
        
        
        /*$crawler = $this->getCrawler();
        $rawDate = $crawler->filter('span.draw-date')->text();
      //  print($rawDate);
        $date = $this->getDate($rawDate);
      //  print_r($date);
        
        $rawDate = $crawler->filter('span.draw-number')->text();
        $drawNo=$this->getDrawNo($rawDate);
        
        $ballsNodes = $crawler->filter('div.numbers-wrapper.drawn-number');
        // print_r($ballsNodes);
        $ballsCnt = 6;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if($ballsCnt == 0)
                break;
            
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }
        print_r($balls); 
       */
        
           $crawler = $this->getCrawler();
      
 $arrNode=$crawler->filter('span.draw-date')->each(function ($node, $i)
{ return $node->nodeValue ;

});
 //print_r($arrNode);
$dt= $this->draw->getDate()->format("D d/M/y,");
//print_r($dt);
$key = array_search($dt, $arrNode);
//print_r($key);
 if($key === false) {
            return 0;
        }  
 
        
        
         $rawDate = trim($crawler->filter('span.draw-number')->eq($key)->text());
         $date = $this->draw->getDate();//$this->getDate($rawDate);
       // print_r($date);
         
          $rawDrawNo = trim($crawler->filter('span.draw-number')->eq($key)->text());
         $drawNo=$this->getDrawNo($rawDrawNo);
       //   print($drawNo);
          
          $balls = array();
      for ($i = 0; $i <= 5; $i++) {
            $balls[]=trim($crawler->filter('div.numbers-wrapper div')->eq($key*6+$i)->text());
        }
         
      //   print_r($balls);
        
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
        $this->resultAll->setUCor("parsing");
        
         
         $t->addLottoResultsAll( $this->resultAll);
       }
        
        
            $this->validate($date);
            if($this->hasResult) {
                $result = $this->draw->getResult();
                $result->setResult($balls);
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
        $rawDate = substr($rawDate, 4, 9);
       // print($rawDate);
        $words = explode("/", $rawDate);
        $day = $words[0];
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];

        $date = new \DateTime("$year-$month-$day");
       // print_r($date);
        return $date;
        }
        
        private function getDrawNo($rawNo)
    {
       // $rawNo=  trim($rawNo);
      //  $rawNo=substr($rawNo, -5);
        $rawNo=  trim($rawNo, "Draw ");
       
         return  $rawNo;
         
    }
}
