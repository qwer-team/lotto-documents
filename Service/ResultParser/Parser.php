<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

interface Parser
{
    public function setDraw($draw);
    //public function getUrl();
    public function parse();
    public function hasResults();
}