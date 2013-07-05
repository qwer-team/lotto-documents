<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\HongKongLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class HongKongLotoParserTest extends \PHPUnit_Framework_TestCase {
    
     public function testParser() {
         
         $file = file_get_contents(__DIR__.'/hongkong.aspx');
         
         $parser = new HongKongLotoParser();
         $draw = new Draw();
         $result = new Result();
         $draw->setResult($result);
         $date = new \DateTime("2013-07-02");
         $draw->setDate($date);
         $parser->setHtmlPage($file);
         $parser->setDraw($draw);
         $parser->parse();
         
         $this->assertTrue($parser->hasResults());
         $this->assertEquals($result->getAllBalls(), array(7, 19, 28, 33, 43, 45, 13));
     }
}
?>
