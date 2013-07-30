<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland\IrishLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class IrishLotoParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParser()
    {
        $file = file_get_contents(__DIR__.'/results-2013.html');
        $crawler = new Crawler($file);
        $parser = new IrishLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-27");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(9, 22, 37, 40, 42, 44, 25));
    }
}
