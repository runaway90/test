<?php

namespace App\Services\Allegro;

use App\Controller\Client\Traits\APIResponseTrait;
use App\Controller\Client\Traits\DoctrineManagerTrait;
use App\Entity\AllegroActivateDevice;
use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainAppService
{
    use APIResponseTrait;
    use DoctrineManagerTrait;

    public function checkUser(AllegroUserAccounts $user)
    {
        if (!$user) {
            return $this->apiJsonResponse('User not found, please correct user id in You request!', 500);
        }
    }

    public function checkAccessToken(AllegroUserAccounts $user)
    {
        if (!$user->getAccessAllegroToken()) {
            return $this->apiJsonResponse('User haven`t access token, please register it', 500);
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'test') {
            return $allegroUrl = 'https://allegro.pl.allegrosandbox.pl';
        } else {
            return $allegroUrl = 'https://allegro.pl';
        }
    }

    public function getApiUrl()
    {
        if (getenv('APP_ENV') == 'dev' || getenv('APP_ENV') == 'test') {
            return $allegroUrl = 'https://api.allegro.pl.allegrosandbox.pl';
        } else {
            return $allegroUrl = 'https://api.allegro.pl';
        }
    }

//    /**
//     * @param AllegroActivateDevice $device
//     * @return Response|null
//     */
//    public function validateDevice(AllegroActivateDevice $device): ?Response
//    {
//        switch ($device) {
//            case($device->getFinishAt() <= Carbon::now()):
//                return Response::create('Time for activation code device finished', 406);
//            case($device->getCreateAt() < Carbon::now()):
//                var_dump($device->getCreateAt(), Carbon::now());
//                return Response::create('Time for activation code device not started', 406);
//            case($device->getRequestIntervalTime() > Carbon::now()):
//                return Response::create('Please wait and try to activate code device after one minute', 406);
//            case(!$device->getDeviceCode() or !$device->getUserCode() or !$device->getVarificationUriComplited()):
//                return Response::create('Already You have Device code, but You need refresh it, please get code device once more time', 406);
//        }
//
//
//        return null;
//    }
//
//    /**
//     * @param AllegroTokens $token
//     * @return Response|null
//     */
//    public function validateToken(AllegroTokens $token): ?Response
//    {
//        switch ($token) {
//            case($token->getFinishTo() >= Carbon::now()):
//                return Response::create('Time for access token finished', 406);
//            case($token->getCreateAt() <= Carbon::now()):
//                return Response::create('Time for access token not started', 406);
//            case($token->getRefreshToken() == null):
//                return Response::create('You haven`t refresh token, please contact with ', 406);
//            case(!$token->getDeviceCode() or !$token->getUserCode() or !$token->getVarificationUriComplited()):
//                return Response::create('Already You have Device code, but You need refresh it, please get code device once more time', 406);
//        }
//
//        return null;
//    }


}
