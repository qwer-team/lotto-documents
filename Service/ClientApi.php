<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Client;

class ClientApi
{

    public function hasEnoughFunds($amount, Client $client, $externalId = null)
    {
        return true;
    }

}