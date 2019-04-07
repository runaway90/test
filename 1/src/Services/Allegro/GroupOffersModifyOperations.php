<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;

class GroupOffersModifyOperations extends MainAppService
{
    const LOG_OP_PRICE_CHANGE = 'price_change_group_modify';
    const LOG_OP_GET_INFO_COMMAND_FOR_PRICE_CHANGE = 'get_info_for_price_command';

    const LOG_OP_QUANTITY_CHANGE = 'quantity_change_group_modify';
    const LOG_OP_GET_INFO_COMMAND_FOR_QUANTITY_CHANGE = 'get_info_for_quantity_command';

    const LOG_OP_OFFER_GROUP_MODIFY = 'modify_group_modify';
    const LOG_OP_GET_INFO_COMMAND_FOR_OFFER_GROUP_MODIFY = 'get_info_for_modify_command';

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

    //price
    public function priceChange(AllegroUserAccounts $account, $body)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offer-price-change-commands/' . $body->commandId, [
                'headers' => $client->getSimpleAuth($token),
                'json' => $body->content
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_PRICE_CHANGE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function priceStatusOrTasks(AllegroUserAccounts $account, $body, $tasks = null)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-price-change-commands/' . $body->commandId . $tasks, [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_INFO_COMMAND_FOR_PRICE_CHANGE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }


    //quantity
    public function quantityChange(AllegroUserAccounts $account, $body)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offer-quantity-change-commands/' . $body->commandId, [
                'headers' => $client->getSimpleAuth($token),
                'json' => $body->content
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_QUANTITY_CHANGE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function quantityStatusOrTasks(AllegroUserAccounts $account, $body, $tasks = null)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-quantity-change-commands/' . $body->commandId . $tasks, [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_INFO_COMMAND_FOR_QUANTITY_CHANGE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }


    //modify
    public function modifyOffer(AllegroUserAccounts $account, $body)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offer-modification-commands/' . $body->commandId, [
                'headers' => $client->getSimpleAuth($token),
                'json' => $body->content
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_OFFER_GROUP_MODIFY,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function modifyStatusOrTasks(AllegroUserAccounts $account, $body, $tasks = null)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-modification-commands/' . $body->commandId . $tasks, [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_INFO_COMMAND_FOR_OFFER_GROUP_MODIFY,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }
}