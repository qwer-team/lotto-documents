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
            "token"   => $token->getToken(),
        );

        $response = $this->makeRequest($fundsUrl, $data);
        $response = json_decode($response);
        return $response->result == 'success';
    }

    public function sendBetsResult($bets, Client $client)
    {
        $url = $client->getResultUrl();
        $request = array();
        $request["data"] = $this->serializeRes($bets);
        $response = $this->makeRequest($url, $request);
        //$response = json_decode($response);
        //if ! result == 'success' записать в лог
        //return $response->result == 'success';
    }
    
    public function sendBetsRallback($bets, Client $client)
    {
        $url = $client->getRollbackUrl();
        $request = array();
        $request["data"] = $this->serialize($bets);
        $this->makeRequest($url, $request);
    }
    
    public function sendBetsCreated($bets, Client $client, $token){
        $url = $client->getBetsUrl();
        $request = array();
        $request["data"] = $this->serialize($bets);
        $request["token"] = $token;
         $response = $this->makeRequest($url, $request);
        //$response = json_decode($response);
        //if ! result == 'success' записать в лог
        //return $response->result == 'success';
    }

    public function makeRequest($url, $data = null)
    {
        $ch = curl_init($url);
       if($url=="http://lottoclient.my/app_dev.php/bets") {
   //        print_r($data );
           
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
        
        /*
“id”: .., 		//(«серверный» ид ставки)
“externalId”: ..,	// (ид игрока)
“currency”: .., 	//(код валюты)
“summa1”: ..,		// (поставлено)  
"draw_id": ..., 		// id тиража 
"draw_dt": ..., 		// датавремя тиража 
"country_code":...., 	// iso код страны
"lotto_name":...., 	// название лотереи
"stake_str": ..., 	// строка ставки, на что поставил
“is_bonus_include”: 	// включая бонусный шар         
         */
        $betsArr = array();
        foreach ($bets as $bet) {
           // if($bet->getSumma2()>0) {
            $arr = array();
            $arr["id"] = $bet->getId();
            $arr["externalId"] = $bet->getExternalUserId();
            $arr["currency"] = $bet->getCurrency()->getCode();
            $arr["summa1"] = $bet->getSumma1();
            $arr["bet_type"] = $bet->getBetType()->getName();
            $arr["draw_id"] = $bet->getLottoDraw()->getId();
            $arr["draw_dt"] = $bet->getLottoDraw()->getDate()->format("d.m.Y H:i");
            $arr["country_code"] = $bet->getLottoDraw()->getLottoTime()->getLottoType()->getCountry()->getCode();
            $arr["lotto_name"] = $bet->getLottoDraw()->getLottoTime()->getLottoType()->getTitle();
            $arr["stake_str"] = implode(",", $bet->getBalls());
            $arr["is_bonus_include"] = $bet->getWithBonus();
       
            //$arr["summa2"] = $bet->getSumma2();

            $betsArr[] = $arr;
            //}
        }

        return json_encode(array("bets" => $betsArr));
    }
    
    
    
     private function serializeRes($bets)
    {
        
        /*
“id”: .., 		//(«серверный» ид ставки)
“externalId”: ..,	// (ид игрока)
“currency”: .., 	//(код валюты)
“summa1”: ..,		// (поставлено)  
"summa2": ..., 		// выиграно 
"result_str": ..., 	// строка ставки, на что поставил
         */
        $betsArr = array();
        foreach ($bets as $bet) {
           // if($bet->getSumma2()>0) {
            $arr = array();
            $arr["id"] = $bet->getId();
            $arr["externalId"] = $bet->getExternalUserId();
            $arr["currency"] = $bet->getCurrency()->getCode();
            $arr["summa1"] = $bet->getSumma1();
            $arr["summa2"] = $bet->getSumma2();
            $arr["result_str"] = implode(",", $bet->getLottoDraw()->getResult()->getAllBalls());
            //$arr["summa2"] = $bet->getSumma2();

            $betsArr[] = $arr;
            //}
        }

        return json_encode(array("bets" => $betsArr));
    }
    
    

}