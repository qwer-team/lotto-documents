<?php

namespace Qwer\LottoDocumentsBundle\Service;

interface BetLineGenerator
{
    public function getBetLines(array $balls);
}