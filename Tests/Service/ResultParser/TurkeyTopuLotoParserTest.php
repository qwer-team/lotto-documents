<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\TurkeyTopuLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class TurkeyTopuLotoParserTest extends \PHPUnit_Framework_TestCase {
    
     public function testParser() {
         
         $file = file_get_contents(__DIR__.'/topu.htm');
         $crawler = new Crawler($file);
         $parser = new TurkeyTopuLotoParser();
         $draw = new Draw();
         $result = new Result();
         $draw->setResult($result);
         $date = new \DateTime("2013-07-03");
         $draw->setDate($date);
         $parser->setCrawler($crawler);
         $parser->setDraw($draw);
         $parser->parse();
         
         $this->assertTrue($parser->hasResults());
         $this->assertEquals($result->getAllBalls(), array(1, 9, 11, 20, 27, 10));
     }
}
