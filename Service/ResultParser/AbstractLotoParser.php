<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;
use Goutte\Client;
use Qwer\LottoBundle\Entity\ResultAll;

abstract class AbstractLotoParser 
{
    protected $draw;
    protected $repoResAll;
    protected $resultAll;
    protected $hasResult = false;
    protected $crawler = null;
    protected $htmlPage = null;
    
    public function __construct() {
        
       $this->resultAll =new ResultAll();
    }

    public function getUrl()
    {
       // echo($this->draw->getLottoTime()->getLottoType()->getUrl());
        return ($this->templateUrl=="")?$this->draw->getLottoTime()->getLottoType()->getUrl():$this->templateUrl;
    }
    
    public function hasResults()
    {
        return $this->hasResult;
    }
    
    public function setCrawler($crawler)
    {
        $this->crawler = $crawler;
    }
    public function setHtmlPage($htmlPage) 
    {
        $this->htmlPage = $htmlPage;
    }

    
    public function setDraw($draw)
    { 
        $this->draw = $draw;
       
    }
    
    public function setRepoResAll($repoResAll)
    {  
        $this->repoResAll = $repoResAll;
       
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
            $client->setHeader('User-Agent', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:22.0) Gecko/20100101 Firefox/22.0');
            $url = $this->getUrl();
            $crawler = $client->request("GET", $url);
            $array = (array) $crawler; 
         //   print_r($array);
        } elseif ($this->crawler != null) {
            $crawler = $this->crawler;
        }
        
        return $crawler;
    }
    
    public function getHtmlPage() {
        if(is_null($this->htmlPage)) {
            $htmlPage = file_get_contents($this->templateUrl);
        }
        else $htmlPage = $this->htmlPage;
     //   print($this->templateUrl."<br/>");
      //  print($this->templateUrl."<br/>");
        return $htmlPage;
    }
    
     
}
?>