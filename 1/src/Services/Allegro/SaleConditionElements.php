<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SaleConditionElements extends MainAppService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;
    /**
     * @var OperationLogger $logger
     */
    protected $logger;

    /**
     * CategoryElements constructor.
     * @param EntityManagerInterface $entityManager
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $entityManager, OperationLogger $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function getGuarantyVariants(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/after-sales-service-conditions/warranties?seller.id=' . $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getImpliedVariants(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/after-sales-service-conditions/implied-warranties?seller.id=' . $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getReturnPolitic(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/after-sales-service-conditions/return-policies?seller.id=' . $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getAdditionalServices(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/sale/offer-additional-services/groups?user.id=' . $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getContactInfo(AllegroUserAccounts $account)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/sale/offer-contacts?seller.id=' . $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

}