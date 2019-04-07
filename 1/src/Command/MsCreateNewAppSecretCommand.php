<?php

namespace App\Command;

use App\Entity\MicroserviceApplication;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\AccountService;
use App\Services\Allegro\CategoryElements;
use App\Services\Microservice\OperationLogger;
use App\Services\Microservice\RegistrationManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MsCreateNewAppSecretCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RegistrationManagerService
     */
    private $registrationManagerService;

    /**
     * @var OperationLogger
     */
    private $logger;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param RegistrationManagerService $registrationManagerService
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $em, RegistrationManagerService $registrationManagerService, OperationLogger $logger)
    {
        $this->em = $em;
        $this->registrationManagerService = $registrationManagerService;
        $this->logger = $logger;
        parent::__construct();
    }


    protected static $defaultName = 'ms:create-new-app-secret';

    protected function configure()
    {
        $this
            ->setDescription('Register new application in MS and add secret_id and secret_key')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of application')
            ->addArgument('url', InputArgument::OPTIONAL, 'Url for communication with application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $url = $input->getArgument('url');

        if ($name && $url) {
            $find = $this->em->getRepository(MicroserviceApplication::class)->findBy(['name' => $name]);
            if (!$find) {
                $find = $this->registrationManagerService->createTestApplication($name, $url);
                $io->note(sprintf('Application ' . strtoupper($name) . ' was created'));
                $io->success('Secret_ID ' . $find->getAppId());
                $io->success('Secret_KEY ' . $find->getAppSecret());
            } else {
                $io->error(sprintf('Application ' . strtoupper($name) . ' already have secret_key and secret_id'));
            }
            $io->success('Finished successfully!');

            $this->logger->logSimpleOperation(
                MicroserviceApplication::LOG_OP_CREATE_SECRET_PASS,
                MicroserviceOperationLogs::APP_LEVEL_DEFAULT,
                get_called_class());
        } else {
            $io->error('Please add name and communication url of application like as string argument after command');
        }
    }
}
