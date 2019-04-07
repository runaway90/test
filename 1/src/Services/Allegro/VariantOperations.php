<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;

class VariantOperations extends MainAppService
{
    const LOG_OP_VAR_CREATE = 'create_variant';
    const LOG_OP_VAR_GET_ONE = 'get_one_variant';
    const LOG_OP_VAR_GET_ALL = 'get_all_variant';
    const LOG_OP_VAR_DELETE = 'delete_variant';

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

    public function create(AllegroUserAccounts $account, $variantId, $variant)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offer-variants/' . $variantId, [
                'headers' => $client->getBetaAcceptAuth($token),
                'json' => $variant
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_VAR_CREATE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function get(AllegroUserAccounts $account, $variantId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-variants/' . $variantId, [
                'headers' => $client->getBetaAcceptAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_VAR_GET_ONE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getAll(AllegroUserAccounts $account)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-variants?user.id='. $account->getSellerId(), [
                'headers' => $client->getBetaAcceptAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_VAR_GET_ALL,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function delete(AllegroUserAccounts $account,$variantId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->delete($this->getApiUrl() . '/sale/offer-variants/' . $variantId, [
                'headers' => $client->getBetaAcceptAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_VAR_DELETE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }
}
