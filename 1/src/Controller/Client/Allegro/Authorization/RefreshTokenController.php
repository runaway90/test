<?php

namespace App\Controller\Client\Allegro\Authorization;

use App\Controller\Client\Allegro\MainAllegroController;
use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\AuthorizationProcess;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController extends MainAllegroController
{
    public function refreshToken(Request $request, AuthorizationProcess $authorization)
    {
        /** @var AllegroUserAccounts|null $user */
        $user = $this->getAndCheckUser($request);
        if ($user == null) {
            return $this->apiJsonResponse($this->userNotFoundError(), 406);
        }

        if ($user instanceof AllegroUserAccounts) {
            /** @var AllegroTokens $getToken */
            $getToken = $user->getAccessAllegroToken();
            $contentForToken = $authorization->getAllegroRefreshToken($getToken);

            /** @var AllegroTokens $token */
            $token = $authorization->refreshToken($contentForToken, $getToken);
            return $this->apiJsonResponse(['refresh_token' => 'Process of refresh token is finish. Access token is - ' . $token->getAccessToken()]);

        } else {
            return $user;
        }

    }

}
