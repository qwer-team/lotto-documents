<?php

use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Qwer\LottoDocumentsBundle\Service\ResultParser\PoolsLotoParser;
use Symfony\Component\DomCrawler\Crawler;

class PoolsLotoParserTest extends \PHPUnit_Framework_TestCase {
    
     public function testParser () {
         
         $file = file_get_contents(__DIR__.'/pools.htm');
         $parser = new PoolsLotoParser();
         
         $draw = new Draw();
         
         $crawler = new Crawler($file);
         $parser->setCrawler($crawler);
         
         $result = new Result();
         $draw->setResult($result);
         
         $date = new \DateTime('2013-06-29');
         $draw->setDate($date);
                  
         $parser->setDraw($draw);
         
         $parser->parse();
         $this->assertTrue($parser->hasResults());
         $this->assertEquals($result->getAllBalls(), array(3, 20, 24, 29, 33, 34, 18));
         
     }
}

?>
