<?php

namespace App\Command;

use App\Controller\Request\RequestController;
use App\Entity\MicroserviceApplication;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MsSendMessageToCoreCommand extends Command
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
        parent::__construct();
    }

    protected static $defaultName = 'ms:send-message-to-core';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

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
                        'commands' => 'update_categories'
                    ],
            ]);
        }
        $this->logger->logSimpleOperation(
            MicroserviceApplication::LOG_OP_SEND_REQUEST_TO_CORE,
            MicroserviceOperationLogs::OP_NAME_REQUEST,
            get_called_class() . '// status -> ' . $response->getStatusCode());

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
