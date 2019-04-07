<?php
/**
 * Created by PhpStorm.
 * User: vito
 * Date: 5.3.2019
 * Time: 15:30
 */

namespace App\Services\Microservice;


use App\Controller\Request\RequestController;
use App\Entity\MicroserviceApplication;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\MainAppService;
use Doctrine\ORM\EntityManagerInterface;

class CoreRequest extends MainAppService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var OperationLogger
     */
    private $logger;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $em, OperationLogger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function sendRequestToCore($command, $object)
    {
        $cores = $this->em->getRepository(MicroserviceApplication::class)->findAll();

        /** @var MicroserviceApplication $core */
        foreach ($cores as $core) {
            $token = $core->getAppId() . ':' . $core->getAppSecret();
            $client = new RequestController();
            $response = $client->createClientRequest()->post($core->getUri(), [
                'headers' => [
                    'Authorization' => "Bearer " . $token,
                    'content-type' => 'application/json',
                    'microservice' => 'allegro'
                ],
                'json' =>
                    [
                        'command' => $command,
                        'object' => $object
                    ],
            ]);
        }
        $this->logger->logSimpleOperation(
            MicroserviceApplication::LOG_OP_SEND_REQUEST_TO_CORE,
            MicroserviceOperationLogs::OP_NAME_REQUEST,
            get_called_class() . '// status -> ' . $response->getStatusCode());
    }

}