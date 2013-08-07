<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser\France;

use Qwer\LottoDocumentsBundle\Service\ResultParser\Parser;
use Goutte\Client;
use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class FranceLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'https://www.fdj.fr/jeux/jeux-de-tirage/loto/resultats';

    public function parse()
    {
        $crawler = $this->getCrawler();
        $rawDate = $crawler->filter('div.resultats-tirage h3.dateTirage')->text();
        $date = $this->getDate($rawDate);
        $ballsNodes = $crawler->filter('div.resultats-tirage div.loto_numeros p.loto_boule');
        $ballsCnt = 5;
        $balls = array();
        foreach ($ballsNodes as $ball) {
            if ($ballsCnt == 0)
                break;
            $balls[] = trim($ball->nodeValue);
            $ballsCnt--;
        }

        $bonus = trim($crawler->filter('div.resultats-tirage div.loto_numeros p.loto_boule_c')->text());

        $this->validate($date);
        if ($this->hasResults()) {
            $result = $this->draw->getResult();
            $result->setResult($balls);
            $result->setBonusResult(array($bonus));
        }
        return $this->hasResults();
    }

    private function getDate($rawDate)
    {
        $frMonth = array(
            'janvier' => 1,
            'février' => 2,
            'mars' => 3,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'août' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'décembre' => 12,
        );
        $words = explode(" ", $rawDate);
        $day = $words[1];
        $month = $frMonth[strtolower($words[2])];
        $year = $words[3];

        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
}