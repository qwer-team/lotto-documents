<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\PrimitivaLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class PrimitivaLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/primitiva.html');
        $crawler = new Crawler($file);
        $parser = new PrimitivaLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-04");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(4, 12, 14, 16, 39, 41, 42));
    }
}
