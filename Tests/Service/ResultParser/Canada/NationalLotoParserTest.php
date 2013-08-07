<?php

use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Canada\NationalLotoParser;

class NationalLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/national.htm');
        $crawler = new Crawler($file);
        $parser = new NationalLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime('2013-06-29');
        $draw->setDate($date);
        $parser->setDraw($draw);
        $parser->setCrawler($crawler);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(04, 11, 12, 14, 25, 37, 36));
        
        
        
    }
}
?>
