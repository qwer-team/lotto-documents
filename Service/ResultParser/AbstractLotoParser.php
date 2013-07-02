<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

abstract class AbstractLotoParser 
{
    protected $draw;
    protected $hasResult = false;
    protected $crawler = null;

    public function getUrl()
    {
        return $this->templateUrl;
    }
    
    public function hasResults()
    {
        return $this->hasResult;
    }
    
    public function setCrawler($crawler)
    {
        $this->crawler = $crawler;
    }

    public function setDraw($draw)
    {
        $this->draw = $draw;
    }
    
    public function validate($date)
    {
        $format = "Y-m-d";
        $this->hasResult = ($date->format($format) == $this->draw->getDate()->format($format));
    }
    
    public function getCrawler()
    {
        if (is_null($this->crawler)) {
            $client = new Client();
            $url = $this->getUrl();
            $crawler = $client->request("GET", $url);
        } else {
            $crawler = $this->crawler;
        }
        
        return $crawler;
    }
}
?>
