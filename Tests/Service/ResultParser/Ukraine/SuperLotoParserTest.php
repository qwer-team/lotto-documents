<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Ukraine\SuperLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class SuperLotoParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {

        $file = file_get_contents(__DIR__ . '/UAsuper.html');
        $crawler = new Crawler($file);
        $parser = new SuperLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-06");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(10, 28, 29, 35, 41, 47));
    }

}
