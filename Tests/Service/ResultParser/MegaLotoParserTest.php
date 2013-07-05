<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\MegaLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class MegaLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParse() {
        $file = file_get_contents(__DIR__.'/megaloto.html');
        $crawler = new Crawler($file);
        $parser = new MegaLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-03");
        $draw->setDate($date);
        $parser->setCrawler($crawler);
        $parser->setDraw($draw);
        //$parser->parse();
        
    }
}