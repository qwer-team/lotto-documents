<?php

namespace Qwer\LottoDocumentsBundle\Exception;


class BetRequestException extends \Exception
{
    /**
     *
     * @var array 
     */
    private $violations;
    
    /**
     * 
     * @return array
     */
    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * 
     * @param array $violations
     */
    public function setViolations($violations)
    {
        $this->violations = $violations;
    }
}