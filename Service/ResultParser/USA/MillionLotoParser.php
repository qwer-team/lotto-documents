<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\USA;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Goutte\Client;
class MillionLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://nylottery.ny.gov/wps/portal';
     
   /*  public function getCrawler()
    {
        if (is_null($this->crawler)) {
            $client = new Client();
            $client->setHeader('User-Agent', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:22.0) Gecko/20100101 Firefox/22.0');
            $url = $this->getUrl();
            $crawler = $client->request("GET", $url);
            $link = $crawler->filter('a:contains("SWEET MILLION")')->link();
            $crawler = $client->click($link);
        } 
        elseif ($this->crawler != null) {
            $crawler = $this->crawler;
        }
        
        return $crawler;
    } */
     
     public function parse() {
         
         $crawler = $this->getCrawler();
      
         $rawDate = trim($crawler->filter('td#LottoHomeTableBG  span.drawings_header')->text());
          
         $date = $this->getDate($rawDate);
         $drawNo=$this->getDrawNo($date->format("Ymd"));
         //print($drawNo."ddd\n");
         
         $ballsNodes = $crawler->filter('div.nyl_numbers')->text();
         $ballsNodes = trim($ballsNodes);
         $ballsNodes = trim($ballsNodes,"." );
        
         $balls=  explode(".", $ballsNodes);
         
         foreach ($balls as $key => $value) {
             $balls[$key]=  trim($value);
         }
         
         $bonusTxt = $crawler->filter('div.nyl_BonusExtra')->text();
         
         $bonus = array(trim($bonusTxt));
         
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
            $this->resultAll->setBonusResult($bonus);
            $this->resultAll->setUCor("parsing");

             $t->addLottoResultsAll( $this->resultAll);
         }
         
         
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
             $result->setBonusResult($bonus);
             $this->draw->setLottoStatus(2);
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
         $rawDate = str_replace('Winning Numbers', '', $rawDate);
         $rawDate = str_replace(',', '', $rawDate);
         $rawDate = str_replace('.', '', $rawDate);
         $rawDate = trim($rawDate);
         $words = explode(' ', $rawDate);
         $day = $words[1];
         $month = $frMonth[strtolower($words[0])];
         $year = date("Y");
         $date = new \DateTime("$year-$month-$day");
         return $date;
      }
      
      private function getDrawNo($rawNo)
    { 
         return trim($rawNo);
         
    }
}
?>
