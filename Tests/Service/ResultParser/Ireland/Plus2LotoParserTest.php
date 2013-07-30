<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland\Plus2LotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class Plus2LotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/plus1.htm');
        $crawler = new Crawler($file);
        $parser = new Plus2LotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-13");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(6, 10, 11, 15, 25, 30, 29));
        }
}
