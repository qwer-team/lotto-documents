<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\TagLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class TagLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/tag.htm');
        $crawler = new Crawler($file);
        $parser = new TagLotoParser();
        $parser->setCrawler($crawler);
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-02");
        $draw->setDate($date);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(4, 0, 1, 2, 6, 5));
        
    } 
}
?>
