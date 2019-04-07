<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;

class AttachmentElements extends MainAppService
{
    protected $typesOfAttachment = ["MANUAL", "SPECIAL_OFFER_RULES", "COMPETITION_RULES", "BOOK_EXCERPT", "USER_MANUAL", "INSTALLATION_INSTRUCTIONS", "GAME_INSTRUCTIONS"];

    const LOG_OP_CREATE_ATTACHMENT = 'create_attachment';

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

    public function addAttachment(AllegroUserAccounts $account, string $type, string $fileUrl)
    {
        /** @var AllegroUserAccounts $account */
        $token = $account->getAccessAllegroToken()->getAccessToken();
        $client = new RequestController();

        try {
            $response = $client->createClientRequest()->post('https://api.allegro.pl.allegrosandbox.pl/sale/offer-attachments', [
                'headers' => $client->getSimpleAuth($token),
                'json' => [
                        'type' => $type,
                        "file" => [
                            "name" => $fileUrl
                        ]
                    ],
            ]);
        } catch (\Exception $e) {
            if (!in_array($type, $this->typesOfAttachment)) {
                return ['error' => "Please check correct Attachment type MANUAL, SPECIAL_OFFER_RULES, COMPETITION_RULES, BOOK_EXCERPT, USER_MANUAL, INSTALLATION_INSTRUCTIONS, GAME_INSTRUCTIONS"];
            } elseif (!$fileUrl && strlen($fileUrl) < 5) {
                return ['error' => "Incorrect file url"];
            }else{
                return ['error' => $e->getMessage()];

            }
        }

        $this->logger->logSimpleOperation(
            self::LOG_OP_CREATE_ATTACHMENT,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class() . '//'.$fileUrl,
            $account);

        $contentsDevice = json_decode($response->getBody()->getContents());
        return $contentsDevice;

    }

}
