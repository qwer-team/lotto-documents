<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Itc\DocumentsBundle\Entity\Document\Transaction as BaseTransaction;

/**
 * Transaction
 */
class Transaction extends BaseTransaction
{
    /**
     *
     * @var string 
     */
    private $inputLevel4;
    
    /**
     *
     * @var string 
     */
    private $outputLevel4;
    
    public function getInputLevel4()
    {
        return $this->inputLevel4;
    }

    public function setInputLevel4($inputLevel4)
    {
        $this->inputLevel4 = $inputLevel4;
    }
    
    public function getOutputLevel4()
    {
        return $this->outputLevel4;
    }

    public function setOutputLevel4($outputLevel4)
    {
        $this->outputLevel4 = $outputLevel4;
    }

}
