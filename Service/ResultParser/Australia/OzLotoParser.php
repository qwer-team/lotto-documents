<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\Australia;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class OzLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'http://tatts.com/tattersalls/results/oz-lotto-latest-results?product=Super7sOzLotto';

    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = $crawler->filter('span.resultHeadingDrawDateSpn')->text();
        $date = $this->getDate($rawDate);
        $ballsNodes = $crawler->filter('span.resultNumberSpn');
        $ballsCnt = 7;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if ($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }

        $bonusNodes = $crawler->filter('span.resultSecondaryNumberColor');
        $bonusCnt = 2;
        $bonus = array();
        foreach($bonusNodes as $bonusBall) {
            if($bonusCnt == 0)
                break;
            $bonus[] = trim($bonusBall->nodeValue);
            $bonusCnt--;
            
        }
        
        
        $this->validate($date);
        if ($this->hasResults()) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult($bonus);
        }
        return $this->hasResults();
    }

    private function getDate($rawDate)
    {
        $frMonth = array(
            'jan' => 1,
            'feb' => 2,
            'mar' => 3,
            'apr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'aug' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dec' => 12,
        );
        $rawDate = substr($rawDate, 20, 9);
        $words = explode("/", $rawDate);
        $day = $words[0];
        $month = $frMonth[strtolower($words[1])];
        $year = $words[2];

        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
}