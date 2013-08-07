<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\USA\MillionLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class MillionLotoParserTest extends \PHPUnit_Framework_TestCase {
    
     public function testParser() {
         
         $file = file_get_contents(__DIR__.'/MILLION.htm');
         $crawler = new Crawler($file);
         $parser = new MillionLotoParser();
         $draw = new Draw();
         $result = new Result();
         $draw->setResult($result);
         $date = new \DateTime("2013-07-01");
         $draw->setDate($date);
         $parser->setCrawler($crawler);
         $parser->setDraw($draw);
         $parser->parse();
         
         $this->assertTrue($parser->hasResults());
         $this->assertEquals($result->getAllBalls(), array(2, 3, 11, 23, 24, 29));
     }
}
?>
