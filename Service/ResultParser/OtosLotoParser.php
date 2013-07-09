<?php

namespace Qwer\LottoDocumentsBundle\Service\ResultParser;

use Qwer\LottoDocumentsBundle\Service\ResultParser\AbstractLotoParser;

class OtosLotoParser extends AbstractLotoParser
{

    protected $templateUrl = 'http://www.szerencsejatek.hu/szerencsenaptar?game=lotto5';

    private function makeRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Host: www.szerencsejatek.hu",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
            "Accept: application/json, text/javascript, */*; q=0.01",
            "Accept-Language: en-us,en;q=0.5",
            "Accept-Encoding: gzip, deflate",
            "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
            "X-Requested-With: XMLHttpRequest",
            "Referer: http://www.szerencsejatek.hu/szerencsenaptar"
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (!is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function parse()
    {

        $data = $this->makeRequest($this->templateUrl);
        $json = json_decode($data);
        
        foreach($json as $key => $value) {
            if ($key == "weeks") 
                $rawDate = $value[0][1];
            elseif ($key == "days") {
                $ballsArray = (array) $value;
                $balls = $ballsArray['_empty_'];
            }
        }
       $date = $this->getDate($rawDate);
       
       $this->validate($date);
       if($this->hasResult) {
           $result = $this->draw->getResult();
           $result->setResult($balls);
       }
       return $this->hasResults();
    }
    
    public function getDate($rawDate) {
        $frMonth = array(
            'január' => 1,
            'február' => 2,
            'március' => 3,
            'április' => 4,
            'május' => 5,
            'június' => 6,
            'július' => 7,
            'augusztus' => 8,
            'szeptember' => 9,
            'október' => 10,
            'november' => 11,
            'december' => 12
        );
        preg_match('/([\d]+)\.\s([\D]+)\s([\d]+)/', $rawDate, $words);
        $day = $words[3];
        $month = $frMonth[$words[2]];
        $year = $words[1];
        $date = new \DateTime("$year-$month-$day");
        return $date;
    }
}
