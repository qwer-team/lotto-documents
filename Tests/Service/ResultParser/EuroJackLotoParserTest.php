<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\EuroJackLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class EuroJackLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/jack.asp');
        $crawler = new Crawler($file);
        $parser = new EuroJackLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-05");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(15, 16, 17, 19, 50, 5, 7));
    }
}
?>
