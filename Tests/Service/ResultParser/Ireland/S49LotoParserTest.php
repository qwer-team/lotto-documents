<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland\S49LotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class S49LotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/s49.aspx');
        $crawler = new Crawler($file);
        $parser = new S49LotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-05");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(2, 5, 21, 24, 28, 43, 34, 9, 12, 15, 17, 18, 32, 7));
    }
}
?>
