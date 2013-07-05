<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\GermanLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class GermanLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/german.html');
        $crawler = new Crawler($file);
        $parser = new GermanLotoParser();
        $parser->setCrawler($crawler);
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-03");
        $draw->setDate($date);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(7, 9, 13, 14, 17, 36, 2));
        
    }
}
?>
