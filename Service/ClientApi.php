<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Client;

class ClientApi
{

    public function hasEnoughFunds($amount, Client $client, $externalId = null)
    {
        return true;
    }

    public function sendBetsResult($bets, Client $client)
    {
        $url = $client->getResultUrl();
        $ch = curl_init($url);
        $request["data"] = $this->serialize($bets);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    private function serialize($bets)
    {
        $betsArr = array();
        foreach($bets as $bet) {
            $arr = array();
            $arr["id"] = $bet->getId();
            $arr["summa1"] = $bet->getSumma1();
            $arr["summa2"] = $bet->getSumma2();
            
            $betsArr[] = $arr;
        }
        
        return json_encode(array("bets" => $betsArr));
    }

}