<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class PhotoElements extends MainAppService
{
    const LOG_OP_SEND_PHOTO = 'send_photo';
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


    public function sendPhotoElement(AllegroUserAccounts $account, string $url = '')
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();

        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->post('https://upload.allegro.pl.allegrosandbox.pl/sale/images', [
                'headers' => $client->getSimpleAuth($token),
                'json' =>
                    [
                        'url' => $url
                    ],
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            PhotoElements::LOG_OP_SEND_PHOTO,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//' . $url,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function createNewLink(AllegroUserAccounts $account, $itemLink, $allegroLink)
    {

    }

}
