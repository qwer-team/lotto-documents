<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class SuperLotoParser extends AbstractLotoParser {
    
     protected $templateUrl = 'http://www.lottery.com.ua/pages/results/loto.php';
     
     public function parse() {
         
         $crawler = $this->getCrawler();
         $rawDate = trim($crawler->filter('tr td span')->text());
         print_r($rawDate);
     }
}
