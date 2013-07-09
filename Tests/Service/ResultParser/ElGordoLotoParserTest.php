<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\ElGordoLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class ElGordoLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser(){
        
        $file = file_get_contents(__DIR__.'/elgordo.asp');
        $crawler = new Crawler($file);
        $parser = new ElGordoLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-07");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(11, 16, 28, 37, 44, 0));
        
    }
}
