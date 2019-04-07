<?php

namespace App\Controller\Request;

use GuzzleHttp\Client;

class RequestController
{
    public function createClientRequest()
    {
        $client = new Client();

        return $client;

    }

    public function getSimpleAuth($token)
    {
        $simpleHeaders = [
            'Authorization' => "Bearer " . $token,
            'Accept' => 'application/vnd.allegro.public.v1+json',
            'content-type' => 'application/vnd.allegro.public.v1+json'
        ];

        return $simpleHeaders;

    }

    public function getUrlencodedAuth($token)
    {
        $simpleHeaders = [
            'Authorization' => "Bearer " . $token,
            'Accept' => 'application/vnd.allegro.public.v1+json',
            'content-type' => 'application/x-www-form-urlencoded'
        ];
        return $simpleHeaders;

    }

    public function getBetaAcceptAuth($token)
    {
        $simpleHeaders = [
            'Authorization' => "Bearer " . $token,
            'Accept' => 'application/vnd.allegro.beta.v1+json',
        ];
        return $simpleHeaders;

    }

}
