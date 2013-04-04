<?php

namespace Qwer\LottoDocumentsBundle\Tests\Validator;

use Qwer\LottoBundle\Entity\Client;
use Qwer\LottoDocumentsBundle\Entity\Currency;
use Qwer\LottoBundle\Entity\Type;
use Itc\DocumentsBundle\Tests\Service\MockFactory;
use Qwer\LottoBundle\Entity\Draw;

/**
 * Description of AbstractValidatorTest
 *
 * @author root
 */
abstract class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var float 
     */
    protected $limit = 1000;

    /**
     *
     * @var float 
     */
    protected $summa = 50;
    
    /**
     *
     * @var float
     */
    protected $minimum = 10;
    
    /**
     *
     * @var float 
     */
    protected $cumLimit = 100;
    
    /**
     *
     * @var array 
     */
    protected $violations = array();

    /**
     *
     * @var type 
     */
    protected $rate = 2.2;
    
    protected function getContextMock()
    {
        $stub = $this->getMockBuilder("Symfony\Component\Validator\ExecutionContextInterface")
        ->disableOriginalConstructor()
        ->getMock();

        $stub->expects($this->any())
        ->method("addViolation")
        ->withAnyParameters()
        ->will($this->returnCallback(array($this, 'violationCallback')));

        return $stub;
    }

    public function violationCallback($message)
    {
        $this->violations[] = $message;
    }

    protected function setUp()
    {
        $this->violations = array();
    }

    protected function getBetMock()
    {
        $stub = $this->getMock("Qwer\LottoDocumentsBundle\Entity\BetLine");

        $rate = 1;
        $currency = new Currency();
        $currency->setRateToMain($rate);

        $client = new Client();
        $client->setCurrency($currency);
        $client->setStaticLimit($this->limit);
        $client->setMinimumLimit($this->minimum);
        $client->setCumulativeLimit($this->cumLimit);

        MockFactory::addMethod($stub, "getClient", $client);
        MockFactory::addMethod($stub, "getCurrency", $currency);
        MockFactory::addMethod($stub, "getLottoType", new Type());
        MockFactory::addMethod($stub, "getLottoDraw", new Draw());

        MockFactory::addMethod($stub, "getBalls", array(1, 2, 3));
        MockFactory::addMethod($stub, "getBallsString", "1_2_3");
        MockFactory::addMethod($stub, "getSumma", $this->summa);
        MockFactory::addMethod($stub, "getRatedSumma", $this->summa * $rate);
        $possibleWin = $this->summa * $rate * ($this->rate - 1);
        MockFactory::addMethod($stub, "getRatedPossibleWin", $possibleWin);

        return $stub;
    }
}