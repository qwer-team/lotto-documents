<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\MaxLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class MaxLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/lotto-max.htm');
        $crawler = new Crawler($file);
        $parser = new MaxLotoParser();
        $parser->setCrawler($crawler);
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-06-28");
        $draw->setDate($date);
        $parser->setDraw($draw);
        
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(2, 7, 15, 38, 43, 44, 47, 1));
    }
}
?>
