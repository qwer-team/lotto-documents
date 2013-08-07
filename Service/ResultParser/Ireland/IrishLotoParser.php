<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Ireland;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class IrishLotoParser extends AbstractLotoParser 
{
    protected $temolateUrl = 'http://www.irishlotto.net/results-2013.html';
    
    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = trim($crawler->filter('div.resultDate h2.style5')->text());
        $date = $this->getDate($rawDate);
        
        $ballsNodes = $crawler->filter('div.resultBall');
        $ballsCnt = 6;
        $balls = array();
        foreach ($ballsNodes as $ball) 
        {
            if($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }
        $bonus = trim($crawler->filter('div.resultBall_bonus')->text());
        $this->validate($date);
        if($this->hasResult) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult(array($bonus));
        }
        return $this->hasResults();
    }
    
    public function getDate($rawDate) {
        $frMonth = array(
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'decembre' => 12
        );
        preg_match('/[\d]+\s[\D]+\s[\d]+/', $rawDate, $strDate);
        $words = explode(' ', $strDate[0]);
        $day = $words[0];
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];
        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
}
