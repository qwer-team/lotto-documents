<?php
use Qwer\LottoDocumentsBundle\Service\ResultParser\Australia\OzLotoParser;
use Symfony\Component\DomCrawler\Crawler;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;

class OzLotoParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {
        $file = file_get_contents(__DIR__.'/oz.htm');
        $crawler = new Crawler($file);
        
        $parser = new OzLotoParser();
        $parser->setCrawler($crawler);
        
        $draw = new Draw();
        $result = new Result();

        $draw->setResult($result);
        $date = new \DateTime("2013-07-02");
        $draw->setDate($date);
        $parser->setDraw($draw);
        
        $parser->parse();
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(45, 13, 38, 42, 31 ,20, 15, 35, 18));
                
    }

}