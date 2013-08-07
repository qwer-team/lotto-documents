<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine\MegaLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class MegaLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParse() {
        $file = file_get_contents(__DIR__.'/megaloto.html');
        $crawler = new Crawler($file);
        $parser = new MegaLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-06");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(3, 4, 7, 20, 28, 35, 3));
        
    }
}