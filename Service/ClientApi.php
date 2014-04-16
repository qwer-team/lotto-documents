<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Qwer\LottoBundle\Entity\Client;
use Qwer\UserBundle\Entity\Token;

class ClientApi
{

    public function hasEnoughFunds($amount, Token $token)
    {
        $client = $token->getClient();
        $currency = $token->getCurrency();
        $externalId = $token->getExternalId();

        $fundsUrl = $client->getFundsUrl();
        $data = array(
            "id" => $externalId,
            "currency" => $currency->getCode(),
            "amount"   => $amount,
        );

        $response = $this->makeRequest($fundsUrl, $data);
        $response = json_decode($response);
        return $response->result == 'success';
    }

    public function sendBetsResult($bets, Client $client)
    {
        $url = $client->getResultUrl();
        $request = array();
        $request["data"] = $this->serialize($bets);
        $this->makeRequest($url, $request);
    }
    
    public function sendBetsRallback($bets, Client $client)
    {
        $url = $client->getRollbackUrl();
        $request = array();
        $request["data"] = $this->serialize($bets);
        $this->makeRequest($url, $request);
    }
    
    public function sendBetsCreated($bets, Client $client){
        $url = $client->getBetsUrl();
        $request = array();
        $request["data"] = $this->serialize($bets);
        $this->makeRequest($url, $request);
    }

    public function makeRequest($url, $data = null)
    {
        $ch = curl_init($url);
       if($url=="http://lottoclient.my/app_dev.php/bets") {
       //    print_r($data );
           
       }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!is_null($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function serialize($bets)
    {
        $betsArr = array();
        foreach ($bets as $bet) {
            $arr = array();
            $arr["id"] = $bet->getId();
            $arr["externalId"] = $bet->getExternalUserId();
            $arr["currency"] = $bet->getCurrency()->getCode();
            $arr["summa1"] = $bet->getSumma1();
            $arr["summa2"] = $bet->getSumma2();

            $betsArr[] = $arr;
        }

        return json_encode(array("bets" => $betsArr));
    }

}