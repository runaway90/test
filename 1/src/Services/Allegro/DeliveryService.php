<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DeliveryService extends MainAppService
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

    public function getAllDeliveryMethods(Request $request)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $account = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['uuid' => $request->headers->get('uuid')]);
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/sale/delivery-methods', [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function createNewShippingRate(Request $request)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $account = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['uuid' => $request->headers->get('uuid')]);
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->post($url . '/sale/shipping-rates', [
            'headers' => $client->getSimpleAuth($token),
            'json' => [
                '' => ''
            ]
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getShippingRates(Request $request)
    {
        $url = $this->getApiUrl();
        /** @var AllegroUserAccounts $account */
        $account = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['uuid' => $request->headers->get('uuid')]);
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get($url . '/sale/shipping-rates?seller.id='. $account->getSellerId(), [
            'headers' => $client->getSimpleAuth($token)
        ]);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

}
