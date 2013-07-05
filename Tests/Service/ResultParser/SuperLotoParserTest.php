<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\SuperLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class SuperLotoParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {

        // $file = 
        // $file = iconv("cp1251", "UTF8", $file);
        $file = new SplFileObject(__DIR__ . '/UAsuper.html');
        $text = "";
        while (!$file->eof()) {
            $text .= $file->current();
            $file->next();
        }
        $crawler = new Crawler($text);
        $parser = new SuperLotoParser();
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
