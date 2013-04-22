<?php

namespace Qwer\LottoDocumentsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class QwerLottoDocumentsBundle extends Bundle
{
    public function getParent()
    {
        return "ItcDocumentsBundle";
    }
}
