<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Hungary\OtosLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class OtosLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/otos.htm');
        $crawler = new Crawler($file);
        $parser = new OtosLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-13");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(2, 13, 15, 36, 69));
    }
}