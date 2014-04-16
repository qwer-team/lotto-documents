<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Turkey;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class SayisalLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lototurkiye.com/SAYISAL-LOTO/CEKILIS-SONUCLARI/1/';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('TR TD a.menucount h2')->text());
         $date = $this->getDate($rawDate);
      //   print_r($date);
         $drawNo=$this->getDrawNo($rawDate);
         
         $ballsNodes = $crawler->filter('tr td a img')->extract(array('src'));
         $ballsNodes = array_unique($ballsNodes);
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             preg_match('/[\d]+\./', $ball, $ballNum);
             $balls[] = str_replace('.', '', $ballNum[0]);
             $ballsCnt--;
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
             'Ocak' => 1,
             'Şubat' => 2,
             'Mart' => 3,
             'Nisan' => 4,
             'Mayıs' => 5,
             'Haziran' => 6,
             'Temmuz' => 7,
             'Ağustos' => 8,
             'Eylül' => 9,
             'Ekim' => 10,
             'Kasım' => 11,
             'Aralık' => 12
         );
         preg_match('/[\d]+\s[\D]+\s[\d]+/', $rawDate, $dateTurk);
         $dtTxt = str_replace('  ', ' ', $dateTurk[0]);
         $dtTxt = trim($dtTxt);
         
         $words = explode(' ', $dtTxt);
      //   print_r($words);
         $day = $words[0];
         $month = $frMonth[$words[1]];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
     }
     
     
     private function getDrawNo($rawNo)
    {   $rawNo= substr($rawNo,0, 4);
        $rawNo= trim($rawNo, ".");
        return trim($rawNo);
         
    }
}
