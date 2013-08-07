<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\USA;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;
use Goutte\Client;
class MillionLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://nylottery.ny.gov/wps/portal';
     
     public function getCrawler()
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
    }
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('div.WinningNumbersText')->text());
         $date = $this->getDate($rawDate);
         
         $ballsNodes = $crawler->filter('tr td div.WinningNumbersResultsSweetMillion');
         $ballsCnt = 6;
         $balls = array();
         foreach ($ballsNodes as $ball) {
             if($ballsCnt == 0)
                 break;
             $balls[] = trim($ball->nodeValue);
             $ballsCnt--;
         }
         $this->validate($date);
         if($this->hasResult) {
             $result = $this->draw->getResult();
             $result->setResult($balls);
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
         $rawDate = str_replace(',', '', $rawDate);
         $words = explode(' ', $rawDate);
         $day = $words[1];
         $month = $frMonth[strtolower($words[0])];
         $year = $words[2];
         $date = new \DateTime("$year-$month-$day");
         return $date;
      }
}
?>
