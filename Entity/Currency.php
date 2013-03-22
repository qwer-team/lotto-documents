<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Currency
 */
class Currency
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $rateToMain;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Currency
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set rateToMain
     *
     * @param float $rateToMain
     * @return Currency
     */
    public function setRateToMain($rateToMain)
    {
        $this->rateToMain = $rateToMain;
    
        return $this;
    }

    /**
     * Get rateToMain
     *
     * @return float 
     */
    public function getRateToMain()
    {
        return $this->rateToMain;
    }
    
    public function __toString()
    {
        return $this->code;
    }

}
