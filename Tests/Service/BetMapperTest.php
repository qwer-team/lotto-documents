<?php

namespace Qwer\LottoDocumentsBundle\Tests\Service;

use Qwer\LottoDocumentsBundle\Service\BetMapper;
use Itc\DocumentsBundle\Tests\Service\MockFactory;
use Qwer\LottoBundle\Entity\Draw;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;
use Qwer\LottoDocumentsBundle\Entity\Request\RawBet;
use Qwer\LottoBundle\Entity\BetType;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoBundle\Entity\Type;
use Qwer\LottoDocumentsBundle\Entity\DocumentType;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoBundle\Entity\Token;
use Qwer\LottoBundle\Entity\User;
use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Currency;

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

        $body->setWithBonus(true);
        $body->setDrawNum(2);
        $type = new Type();
        
        $token = new Token();
        $token->setExternalId(1);
        $user = new User();
        $user->setClient(new Client());
        $token->setCurrency(new Currency());
        $token->setUser($user);
        
        $body->setToken($token);
        $body->setLottoType($type);

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

        $generatorStub = $this->getMock("Qwer\LottoDocumentsBundle\Service\BetLineGenerator");
        //getBetLines

        $generatorStub
        ->expects($this->any())
        ->method("getBetLines")
        ->will($this->returnCallback(array($this, 'generatorCallback')));

        MockFactory::addMethod($stub, "get", $generatorStub);
        MockFactory::addMethod($stub, "has", true);

        return $stub;
    }

    public function generatorCallback($balls)
    {
        $lines = new ArrayCollection();

        $line = new BetLine();
        $line->setBalls($balls);

        $lines->add($line);

        return $lines;
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