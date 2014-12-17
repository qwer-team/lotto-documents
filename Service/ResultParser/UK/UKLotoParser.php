<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\UK;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;


class UKLotoParser extends AbstractLotoParser {
    
  //   protected $templateUrl = 'https://www.national-lottery.co.uk/player/lotto/results/results.ftl';
     protected $templateUrl = '';
     protected  $frMonth = array(
            1 =>  'Jan' , 
            2 =>  'Feb' , 
            3 =>  'Mar' , 
            4 =>  'Apr' , 
            5 =>  'May' , 
            6 =>  'Jun' , 
            7 =>  'Jul' , 
            8 =>  'Aug' , 
            9 =>  'Sep' , 
            10 =>  'Oct' , 
            11 =>  'Nov' , 
            12 =>  'Dec' 
         ); 
     
     public function parse() {
     //    print("daf");
         $crawler = $this->getCrawler();
      //   $rawDate = trim($crawler->filter('.list_table .table_cell_1 .table_cell_block')->text());
$arrNode=$crawler->filter('.list_table .table_cell_1 .table_cell_block')->each(function ($node, $i)
{ return trim($node->nodeValue) ;

});
 //print_r($arrNode);
$d= $this->draw->getDate()->format("D d M Y");
 
 //print($d );
      // Wed 05 Nov 2014 
$key = array_search($d , $arrNode);     
 if($key === false) {
            return 0;
        } 
         
         
         $rawDate = trim($crawler->filter('.list_table .table_cell_1 .table_cell_block')->eq($key)->text());
     //     print($rawDate);
         $date = $this->getDate($rawDate);
        
         $drawNo =  str_replace(' ', '', $rawDate); 
       // print($drawNo."\n");
         
         
        
         $ballsNodes = trim($crawler->filter('.list_table .table_cell_3 .table_cell_block')->eq($key)->text());
         
         $ballsSpace = explode('-', $ballsNodes);
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsSpace as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball);
             $ballsCnt--;
         }
         
         $bonus = trim($crawler->filter('.list_table .table_cell_4 .table_cell_block')->eq($key)->text());
         
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
             
             $frMonth = array(
                 'jan' => 1,
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
                 'dec' => 12
             );
             $rawDate = substr($rawDate, 4);
             $words = explode(' ', $rawDate);
             $day = $words[0];
             $month = $frMonth[strtolower($words[1])];
             $year = $words[2];
             $date = new \DateTime("$year-$month-$day");
             return $date;
         }
}
?>
