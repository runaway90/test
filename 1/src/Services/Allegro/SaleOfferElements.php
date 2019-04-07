<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroCategories;
use App\Entity\AllegroOffer;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class SaleOfferElements extends MainAppService
{
    const LOG_OP_SEND_OFFER_DRAFT = 'send_offer_draft';
    const LOG_OP_CHANGE_OFFER_INFO = 'change_offer_info';
    const LOG_OP_DELETE_DRAFT = 'delete_draft';
    const LOG_OP_PUBLICATION_OFFER = 'publication_offer';
    const LOG_OP_GET_OFFER_INFO = 'get_offer_info';

    const LOG_OP_GET_STATUS_OPERATION = 'get_status_operation';

    const LOG_OP_CHANGE_BUY_NOW_PRICE = 'change_buy_now_price';

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

    public function sendDraft(AllegroUserAccounts $account, $offer)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->post($this->getApiUrl() . '/sale/offers', [
                'headers' => $client->getSimpleAuth($token),
                'json' =>
                    [
                        'name' => $offer->name,
                        'category' => [
                            'id' => $offer->category
                        ]
                    ],
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_SEND_OFFER_DRAFT,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//' . $offer->name,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());

        try {
            $this->wroteOfferToDB($contentsDevice);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }


        return $contentsDevice;

    }

    public function removeDraft(AllegroUserAccounts $account, $offerId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $client->createClientRequest()->delete($this->getApiUrl() . '/sale/offers/' . $offerId, [
                'headers' => $client->getSimpleAuth($token)
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_DELETE_DRAFT,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        return ["message" => 'Draft deleted'];

    }

    public function putOfferInfo(AllegroUserAccounts $account, $offer)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offers/' . $offer->id, [
                'headers' => $client->getSimpleAuth($token),
                'json' => $offer
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_CHANGE_OFFER_INFO,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function getOfferInfo(AllegroUserAccounts $account, $offerId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offers/' . $offerId, [
                'headers' => $client->getSimpleAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_OFFER_INFO,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function changeStatusAndScheduleOffer(AllegroUserAccounts $account, $offerId, string $commandId, string $action = 'ACTIVATE', $scheduleFor = null, string $type = 'CONTAINS_OFFERS')
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        $arrayIds = [];
        if (is_array($offerId)) {
            foreach ($offerId as $id) {
                $newOffersIdStructure[] = ["id" => $id];
                array_merge($arrayIds, $newOffersIdStructure);
            }
        } else {
            $arrayIds = ["id" => $offerId];
        }

        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/sale/offer-publication-commands/' . $commandId, [
                'headers' => $client->getSimpleAuth($token),
                'json' => [
                    "publication" => [
                        'action' => $action,
                        'scheduledFor' => $scheduleFor
                    ],
                    "offerCriteria" => [
                        [
                            'offers' => [
                                $arrayIds,
                            ],
                            'type' => $type
                        ],
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_CHANGE_OFFER_INFO,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//' . $action . "//" . $scheduleFor,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function takeSimpleStatusOffer(AllegroUserAccounts $account, string $commandId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-publication-commands/' . $commandId, [
                'headers' => $client->getSimpleAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_STATUS_OPERATION,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//' . $commandId,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function takeDifficultStatusOffer(AllegroUserAccounts $account, string $commandId)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();
        try {
            $response = $client->createClientRequest()->get($this->getApiUrl() . '/sale/offer-publication-commands/' . $commandId . '/tasks', [
                'headers' => $client->getSimpleAuth($token),
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_GET_STATUS_OPERATION,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//' . $commandId,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    public function changeTheBuyNowPrice(AllegroUserAccounts $account, $offerId, $commandId, string $amount, string $currency)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->put($this->getApiUrl() . '/offers/' . $offerId . '/change-price-commands/' . $commandId, [
                'headers' => $client->getSimpleAuth($token),
                'json' => [
                    "id" => $offerId,
                    "input" => [
                        "buyNowPrice" => [
                            "amount" => $amount,
                            "currency" => $currency
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_CHANGE_BUY_NOW_PRICE,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//',
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

    protected function wroteOfferToDB($offer)
    {
        /** @var  AllegroCategories $category*/
        $category = $this->entityManager->getRepository(AllegroCategories::class)->findOneBy(['allegroId' => $offer->category->id]);
//        if ($category) {
            $createOffer = (new AllegroOffer())
                ->setAllegroId($offer->id)
                ->setAllegroOrder(null)
                ->setCreateAt(Carbon::now())
                ->setCategory($category);

            $this->entityManager->persist($createOffer);
            $this->entityManager->flush();
//        } else {
//            return ['error' => 'Category not found, please update them'];
//        }


    }
}
