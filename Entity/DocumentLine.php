<?php

namespace Qwer\LottoDocumentsBundle\Entity;

use Itc\DocumentsBundle\Entity\Document\DocumentLine as ITCDocumentLine;

abstract class DocumentLine extends ITCDocumentLine
{

    public function getDocumentType()
    {
        return $this->getDocument()->getDocumentType();
    }

}