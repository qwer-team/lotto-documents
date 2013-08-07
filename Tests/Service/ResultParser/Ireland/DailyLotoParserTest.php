<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland\DailyLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Symfony\Component\DomCrawler\Crawler;
use Qwer\LottoBundle\Entity\Result;

class DailyLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/daily-million.asp');
        $crawler = new Crawler($file);
        $parser = new DailyLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-10");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(1, 15, 34, 35, 36, 39, 27));
    }
}
?>
