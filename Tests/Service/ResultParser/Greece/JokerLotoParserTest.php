<?php

use Qwer\LottoDocumentsBundle\Service\ResultParser\Greece\JokerLotoParser;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoBundle\Entity\Result;

class JokerLotoParserTest extends \PHPUnit_Framework_TestCase {
    
    public function testParser() {
        
        $file = file_get_contents(__DIR__.'/joker.htm');
        $parser = new JokerLotoParser();
        $draw = new Draw();
        $result = new Result();
        $draw->setResult($result);
        $date = new \DateTime("2013-06-30");
        $draw->setDate($date);
        $parser->setHtmlPage($file);
        $parser->setDraw($draw);
        
        $parser->parse();
        
        $this->assertTrue($parser->hasResults());
        $this->assertEquals($result->getAllBalls(), array(7, 10, 31, 37, 39, 16));
    }
}
?>
