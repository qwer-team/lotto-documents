<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Itc\DocumentsBundle\Entity\Document\Rest as BaseRest;

class Rest extends BaseRest
{
    /**
     *
     * @var string 
     */
    private $level4;
    
    public function getLevel4()
    {
        return $this->level4;
    }

    public function setLevel4($level4)
    {
        $this->level4 = $level4;
    }

}