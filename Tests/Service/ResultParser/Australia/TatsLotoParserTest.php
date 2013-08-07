<?php
use Qwer\LottoDocumentsBundle\Service\ResultParser\Australia\TatsLotoParser;
use Symfony\Component\DomCrawler\Crawler;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;

class TatsLotoParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {
        $file = file_get_contents(__DIR__.'/tatts.html');
        $crawler = new Crawler($file);
        
        $parser = new TatsLotoParser();
        $parser->setCrawler($crawler);
        
        $draw = new Draw();
        $result = new Result();

        $draw->setResult($result);
        $date = new \DateTime("2013-06-29");
        $draw->setDate($date);
        $parser->setDraw($draw);
        
        $parser->parse();
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(31, 25, 22, 42, 26, 11, 8, 1));
                
    }

}