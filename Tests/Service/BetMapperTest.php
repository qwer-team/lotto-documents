<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service;

use Qwer\LottoDocumentsBundle\Service\BetMapper;
use Itc\DocumentsBundle\Tests\Service\MockFactory;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;
use Qwer\LottoDocumentsBundle\Entity\Currency;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;
use Qwer\LottoBundle\Entity\BetType;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Service\BetLineGenerator\SingleGenerator;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoBundle\Entity\Time;
use Qwer\LottoBundle\Entity\Type;
use Qwer\LottoDocumentsBundle\Entity\DocumentType;

class BetMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \Qwer\LottoDocumentsBundle\Service\BetMapper 
     */
    private $service;

    protected function setUp()
    {
        $this->service = new BetMapper();
        $this->service->setDrawFinder($this->getDrawFinder());
        $this->service->setContainer($this->getContainerMock());
        $this->service->setRateService($this->getRateServiceMock());
        $this->service->setDocumentType(new DocumentType());
    }

    private function getDrawFinder()
    {
        $stub = $this->getMock("Qwer\LottoDocumentsBundle\Service\DrawFinder");

        $draws = new ArrayCollection();

        $draw1 = new Draw();
        $draw1->setDate(new \DateTime("2014-5-5 13:00:00"));

        $draw2 = new Draw();
        $draw2->setDate(new \DateTime("2014-5-12 13:00:00"));

        $draws->add($draw1);
        $draws->add($draw2);

        MockFactory::addMethod($stub, "getDraws", $draws);

        return $stub;
    }

    private function getBody()
    {
        $body = new Body();

        $body->setCurrency(new Currency());
        $body->setExternalId(1);
        $body->setClient(new Client());
        $body->setWithBonus(true);
        $body->setDrawNum(2);
        $time = new Time();
        $type = new Type();
        $time->setLottoType($type);
        $body->setLottoTime($time);

        $rawBets = $this->getRawBets();
        $body->setRawBets($rawBets);

        return $body;
    }

    private function getRawBets()
    {
        $betType = new BetType();
        $betType->setCode("single");

        $rawBet1 = new RawBet();
        $rawBet1->setBalls(array(1, 2, 3));
        $rawBet1->setBetType($betType);
        $rawBet1->setSumma(20);

        $rawBets = new ArrayCollection(array($rawBet1));

        return $rawBets;
    }

    private function getContainerMock()
    {
        $stub = $this->getMockBuilder("Symfony\Component\DependencyInjection\ContainerInterface")
        ->disableOriginalConstructor()
        ->getMock();

        MockFactory::addMethod($stub, "get", new SingleGenerator());
        MockFactory::addMethod($stub, "has", true);

        return $stub;
    }

    public function testMapper()
    {
        $body = $this->getBody();

        $bets = $this->service->getBets($body);

        $betCnt = count($bets);

        $this->assertEquals(2, $betCnt);

        foreach ($bets as $bet) {
            $this->assertEquals(array(1, 2, 3), $bet->getBalls());
            $this->assertEquals(20, $bet->getSumma());

            $this->assertEquals(1, count($bet->getDocumentLines()));

            foreach ($bet->getDocumentLines() as $line) {
                $this->assertEquals(20, $line->getSumma());
                $this->assertEquals(array(1, 2, 3), $line->getBalls());
            }
        }
    }

    private function getRateServiceMock()
    {
        $stub = $this->getMock("Qwer\LottoBundle\Service\RateService");

        $stub->expects($this->any())
        ->method("getRate")
        ->withAnyParameters()
        ->will($this->returnValue(2.2));

        return $stub;
    }

}