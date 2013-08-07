<?php
use Qwer\LottoDocumentsBundle\Service\ResultParser\France\FranceLotoParser;
use Symfony\Component\DomCrawler\Crawler;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;

class FranceLotoParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {
        $file = file_get_contents(__DIR__.'/franceloto.html');
        $crawler = new Crawler($file);
        
        $parser = new FranceLotoParser();
        $parser->setCrawler($crawler);
        
        $draw = new Draw();
        $result = new Result();

        $draw->setResult($result);
        $date = new \DateTime("2013-07-01");
        $draw->setDate($date);
        $parser->setDraw($draw);
        
        $parser->parse();
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(5, 21, 31, 37, 38 ,6));
                
    }

}