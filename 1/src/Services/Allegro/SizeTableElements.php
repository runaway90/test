<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use Symfony\Component\HttpFoundation\Response;

class SizeTableElements extends MainAppService
{
    public function allSizeTables(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();

        /** @var Response $response */
        try {
            $response = $client->createClientRequest()->get($url . '/sale/size-tables?user.id=' . $account->getSellerId(), [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return false;
        }

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function oneTable(AllegroUserAccounts $account, $table)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        /** @var Response $response */
        try {
            $response = $client->createClientRequest()->get($url . '/sale/size-tables/' . $table, [
                'headers' => $client->getSimpleAuth($token)
            ]);

        } catch (\Exception $e) {
            return false;
        }
        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

}
