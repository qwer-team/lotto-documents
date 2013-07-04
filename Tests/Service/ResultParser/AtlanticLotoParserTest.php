<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\AtlanticLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class AtlanticLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/atlantic.htm');
        $crawler = new Crawler($file);
        $parser = new AtlanticLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-06-29");
        $parser->setCrawler($crawler);
        $draw->setDate($date);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(7, 16, 23, 24, 29, 35, 40));
    }
}
?>
