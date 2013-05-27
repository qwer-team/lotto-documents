<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;
use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;


class IrishLottoParser implements Parser
{
    private  $templateUrl = 'http://www.irishlotto.net/results-{year}.html';
    public function getUrl()
    {
        $search = array("{year}");
        $replace = array(date('Y'));
        $url = str_replace($search, $replace, $this->templateUrl);
        return $url;
    }

    public function hasResults()
    {
        
    }

    public function parse()
    {
        $url = $this->getUrl();
    }

    public function setDraw($draw)
    {
        
    }
}