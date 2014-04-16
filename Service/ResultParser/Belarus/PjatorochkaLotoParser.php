<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Belarus;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Liuggio\ExcelBundle;
 
 
class PjatorochkaLotoParser extends AbstractLotoParser  {
    
     protected $templateUrl = 'http://belloto.by/peterochka/5ka-akts/';
     
     public function parse() {
        
          $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('table.tirag_table tr td')->text());
         $ar= explode("/", $rawDate);
         $rawDate =trim($ar[1]);
         $date = $this->getDate($rawDate);
    //     print_r($date);
          $drawNo=trim( $ar[0]);
     // print($drawNo." \n"); tirag_icon
         $a=trim($crawler->filter('a.tirag_icon')->attr('href'));  
          
          
        $fr = fopen("http://belloto.by".$a, "r");
        $fw = fopen("x.xsl","w");
        $c= stream_get_contents($fr);
        fwrite($fw, $c);
        fclose($fr);
        fclose($fw);
         $f= new ExcelBundle\Factory();
        $phpExcelObject = $f->createPHPExcelObject("x.xsl");
        //$phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject("x.xsl");
        $ballsNodes= $phpExcelObject->getActiveSheet()->getCell('A8')->getValue();
      
        $ballsNodes= trim(str_replace("Порядок выпадения чисел:", "",$ballsNodes));
         $balls=  explode(", ", $ballsNodes);
   //     print_r($balls);
         
         
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
     public function getDate($rawDate) {
         $frMonth = array(
             'января' => 1,
             'февраля' => 2,
             'марта' => 3,
             'апреля' => 4,
             'мая' => 5,
             'июня' => 6,
             'июля' => 7,
             'августа' => 8,
             'сентября' => 9,
             'октября' => 10,
             'ноября' => 11,
             'декабря' => 12
         );
        $words = explode(' ', $rawDate);
         $day = $words[0];
         $month = $frMonth[strtolower($words[1])];  
         $year=date("Y");
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
      private function getDrawNo($rawNo)
    {
        $rawNo= str_replace("№", "",$rawNo); 
        $rawNo = trim($rawNo);
         return  $rawNo;
         
    }
}
?>
