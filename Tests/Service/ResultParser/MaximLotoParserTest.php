<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\MaximLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class MaximLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParse() {
        
        $file = file_get_contents(__DIR__.'/lotomx.htm');
        $crawler = new Crawler($file);
        $parser = new MaximLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-07");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(8, 29, 35, 42, 45));
    }
}
?>
