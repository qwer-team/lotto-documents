<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\UK\UKLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class UKLotoParserTest extends \PHPUnit_Framework_TestCase {
    
     public function testParser() {
         
         $file = file_get_contents(__DIR__.'/UK.ftl');
         $crawler = new Crawler($file);
         $parser = new UKLotoParser();
         $draw = new Draw();
         $result = new Result();
         $draw->setResult($result);
         $date = new \DateTime("2013-07-03");
         $draw->setDate($date);
         $parser->setCrawler($crawler);
         $parser->setDraw($draw);
         $parser->parse();
         
         $this->assertTrue($parser->hasResults());
         $this->assertEquals($result->getAllBalls(), array(15, 27, 31, 40, 41, 49, 1));
     }
}
?>
