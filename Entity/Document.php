<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Itc\DocumentsBundle\Entity\Document\Document as ITCDocument;

abstract class Document extends ITCDocument
{
    /**
     * @var Currency
     */
    protected $currency;
    
    /**
     * Set currency
     *
     * @param Currency $currency
     * @return Bet
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}