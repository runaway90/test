<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;

class ServicePointElements extends MainAppService
{
    const LOG_OP_CREATE_SP = 'create_service_point';
    const LOG_OP_GET_ALL_SP = 'get_all_service_point';
    const LOG_OP_GET_DETAIL_SP = 'get_detail_service_point';
    const LOG_OP_MODIFY_SP = 'modify_service_point';
    const LOG_OP_DELETE_SP = 'delete_service_point';

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

    public function create(AllegroUserAccounts $account, $point)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        $point->seller = ['id' => $account->getSellerId()];
        try {
            $response = $client->createClientRequest()->post($this->getApiUrl() . '/points-of-service', [
                'headers' => $client->getSimpleAuth($token),
                'json' => $point
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_CREATE_SP,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getPoint(AllegroUserAccounts $account)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/points-of-service?seller.id='.$account->getSellerId(), [
                'headers' => $client->getSimpleAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_ALL_SP,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getPointDetail(AllegroUserAccounts $account, $id)
    {
/** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/points-of-service/'. $id, [
                'headers' => $client->getSimpleAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_DETAIL_SP,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function modify(AllegroUserAccounts $account, $point)
    {
/** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/points-of-service/'. $point->id, [
                'headers' => $client->getSimpleAuth($token),
                'json' => $point
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_MODIFY_SP,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function delete(AllegroUserAccounts $account, $id)
    {
/** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->delete($this->getApiUrl() . '/points-of-service/'. $id, [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_DELETE_SP,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

}
