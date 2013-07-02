<?php 

use Qwer\LottoDocumentsBundle\Service\ResultParser\Super66LotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;
use Symfony\Component\DomCrawler\Crawler;

class Super66LotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser () {
    
        $file = file_get_contents(__DIR__.'/super66.htm');
        $crawler = new Crawler($file);
        $parser = new Super66LotoParser();
        $parser->setCrawler($crawler);
        
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        
        $date = new \DateTime('2013-06-29');
        $draw->setDate($date);
        $parser->setDraw($draw);
        
        $parser->parse();
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(4, 1, 0, 9, 7, 2));
        
        

    }
    
}

?>
