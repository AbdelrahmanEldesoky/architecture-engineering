<?php

namespace App\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
trait SmsTrait
{

    public function sendSmsMessageMora($to,$message)
    {
        $to = is_array($to) ? $to : (array)$to;

        $addCode = function ($number){
            return '966'.ltrim(trim($number), '0');
        };
        $numbers = array_map($addCode, $to);
        // "966538500542,966545550161"
        $url = "https://mora-sa.com/api/v1/sendsms?";
        $push_payload = array(
            "api_key" => "9d058ee9354f6f18628a67a3ee542e452b2ced61",
            "username" => "saadmashal",
            "sender" => "Vision Dim",
            "numbers" => json_encode($numbers),
            "message" => $message,
            "response" => "text",
        );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url.http_build_query($push_payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
    }


    // private function sendSmsMessage($url,$data){
    //     try {
    //         $client = new Client();
    //         $response = $client->request('GET',$url.http_build_query($data), [
    //                 'headers' => ['Content-Type' => 'application/json','Accept' => 'application/json'],
    //                 ]);
    //                 return $response;
    //         // return json_decode($response->getBody());
    //     } catch ( ClientException $exception ) {
    //         return $exception->getResponse()->getBody();
    //     }
    // }
}
