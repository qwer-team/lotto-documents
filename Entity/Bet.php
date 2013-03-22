<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bet
 */
class Bet extends Document
{

    /**
     * @var string
     */
    protected $externalUserId;

    /**
     * @var \Qwer\LottoBundle\Entity\Client
     */
    protected $lotoClient;

    /**
     *
     * @var int 
     */
    protected $documentTypeId = 1;

    /**
     * Set externalUserId
     *
     * @param string $externalUserId
     * @return Bet
     */
    public function setExternalUserId($externalUserId)
    {
        $this->externalUserId = $externalUserId;

        return $this;
    }

    /**
     * Get externalUserId
     *
     * @return string 
     */
    public function getExternalUserId()
    {
        return $this->externalUserId;
    }

    /**
     * Set lotoClient
     *
     * @param \Qwer\LottoBundle\Entity\Client $lotoClient
     * @return Bet
     */
    public function setLotoClient(\Qwer\LottoBundle\Entity\Client $lotoClient = null)
    {
        $this->lotoClient = $lotoClient;

        return $this;
    }

    /**
     * Get lotoClient
     *
     * @return \Qwer\LottoBundle\Entity\Client 
     */
    public function getLotoClient()
    {
        return $this->lotoClient;
    }

    public function addBetLine(BetLine $betLine)
    {
        $this->addDocumentLine($betLine);
        
        $summa = $this->getSumma1() + $betLine->getSum();
        $this->setSumma1($summa);
    }

}
