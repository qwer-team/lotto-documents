<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Greece\GreeceLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;

class GreeceLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/greece.htm');
        $parser = new GreeceLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-07-03");
        $draw->setDate($date);
        $parser->setHtmlPage($file);
        $parser->setDraw($draw);
        
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(13, 22, 24, 33, 48, 49, 7));
    }
}
?>
