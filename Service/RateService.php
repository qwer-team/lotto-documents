<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Type;
use Qwer\LottoBundle\Entity\Client;

class RateService
{

    public function getRate(array $balls, $withBonus, Type $lottoType, Client $client )
    {
        throw new \Exception("not implemented yet");
    }

}