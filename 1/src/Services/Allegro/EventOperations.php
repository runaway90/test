<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroEvent;
use App\Entity\AllegroOffer;
use App\Entity\AllegroOrder;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class EventOperations extends MainAppService
{
    const LOG_OP_GET_ORDER_EVENTS = 'order_events';
    const LOG_OP_GET_FROM_LAST_EVENT = 'events_from_last_';
    const LOG_OP_GET_EVENT_STATISTICS = 'event_statistics';
    const LOG_OP_GET_USER_ORDERS = 'user_orders';
    const LOG_OP_GET_ORDER_DETAILS = 'order_details';
    const LOG_OP_LIST_OF_PARCEL_TRACKING = 'list_of_tracking';
    const LOG_OP_ADD_PARCEL_TRACKING = 'add_parcel';
    const LOG_OP_MAPPING_LINE_ITEM = 'mapping_line_item';

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

    public function getOrderEvents(AllegroUserAccounts $account)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/events', [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_ORDER_EVENTS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getOrderEventsFromLastId(AllegroUserAccounts $account, $eventId)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/events?from=' . $eventId, [
                'headers' => $client->getBetaAcceptAuth($token),
//                'json' =>
//                    ["client_id" => "231072000f124f1486c58e07d964b377"]
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_ORDER_EVENTS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getOrderEventStatistics(AllegroUserAccounts $account)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/event-stats', [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_EVENT_STATISTICS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getUserOrders(AllegroUserAccounts $account)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/checkout-forms', [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_USER_ORDERS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getOrderDetails(AllegroUserAccounts $account, $id)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/checkout-forms/' . $id, [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_ORDER_DETAILS,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getTracking(AllegroUserAccounts $account, $id)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/checkout-forms/' . $id . '/shipments', [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_LIST_OF_PARCEL_TRACKING,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function createTrackingParcel(AllegroUserAccounts $account, $id, $json)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->post($this->getApiUrl() . '/order/checkout-forms/' . $id . '/shipments', [
                'headers' => $client->getBetaAcceptAuth($token),
                'json' => $json
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_ADD_PARCEL_TRACKING,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function getMappingLineItem(AllegroUserAccounts $account, $lineItemId = null, $dealId = null)
    {

        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        if ($lineItemId) {
            $routeAdd = '?lineItemId=' . $lineItemId;
        } elseif ($dealId) {
            $routeAdd = '?dealId=' . $lineItemId;
        } else {
            return ['error' => 'Add to request parameter "lineItemId" or "dealId".'];
        }

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/order/line-item-id-mappings' . $routeAdd, [
                'headers' => $client->getBetaAcceptAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_LIST_OF_PARCEL_TRACKING,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;
    }

    public function writeEventToDB($event, $account)
    {
        $createEvent = (new AllegroEvent())
            ->setAllegroId($event->id)
            ->setCreateAt($event->occurredAt)
            ->setUserAccount($event)
            ->setType($event->type);

        $order = $this->entityManager->getRepository(AllegroOrder::class)->findByCheckoutFormId($event->order->checkoutForm->id);
        if (!$order) {
            $createdOrder = (new AllegroOrder())
                ->setBuyerId($event->order->buyer->id)
                ->setCheckoutFormId($event->order->checkoutForm->id)
                ->setAllegroEvents($createEvent);

            $this->entityManager->persist($createdOrder);
        } else {
            $createEvent->addAllegroOrder($order);
            $createdOrder = $order;
        }

        $offer = $this->entityManager->getRepository(AllegroOffer::class)->findByAllegroId($event->order->lineItems->offer->id);
        if (!$offer) {
            $createOffer = (new AllegroOffer())
                ->setAllegroId($event->order->lineItems->offer->id)
                ->setAllegroOrder($createdOrder)
                ->setCreateAt($event->order->lineItems->offer->boughtAt);

            $this->entityManager->persist($createOffer);
        }else{
            $createdOrder->addOffer($offer);
            $this->entityManager->persist($createdOrder);
        }

        $this->entityManager->persist($createEvent);

        $this->entityManager->flush();

    }

}
