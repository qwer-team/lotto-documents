<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\SayisalLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class SayisalLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/sayisal.htm');
        $crawler = new Crawler($file);
        $parser = new SayisalLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-06-29");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(5, 14, 16, 17, 35, 39));
    }
}
