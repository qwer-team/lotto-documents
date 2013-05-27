<?php

namespace Qwer\LottoDocumentsBundle\Service\BetLineGenerator;

use Qwer\LottoDocumentsBundle\Service\BetLineGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Qwer\LottoDocumentsBundle\Entity\BetLine;
use Qwer\LottoDocumentsBundle\Math\GeneratorVariation;

class FiveFoldsGenerator extends AbstractVariationsGenerator
{
    protected $n = 5;
}