<?php

namespace App\Command;

use App\Entity\AllegroActivateDevice;
use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\AccountService;
use App\Services\Allegro\AuthorizationProcess;
use App\Services\Microservice\OperationLogger;
use App\Services\Microservice\RegistrationManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MsFirstCommand extends Command
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
     * @var AuthorizationProcess
     */
    private $authorization;

    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param RegistrationManagerService $registrationManagerService
     * @param AuthorizationProcess $authorization
     * @param OperationLogger $logger
     * @param AccountService $accountService
     */
    public function __construct(EntityManagerInterface $em,
                                AccountService $accountService,
                                RegistrationManagerService $registrationManagerService,
                                AuthorizationProcess $authorization,
                                OperationLogger $logger)
    {
        $this->em = $em;
        $this->registrationManagerService = $registrationManagerService;
        $this->authorization = $authorization;
        $this->accountService = $accountService;
        $this->logger = $logger;
        parent::__construct();
    }

    protected static $defaultName = 'ms:first-command';

    protected function configure()
    {
        $this
            ->setDescription('First command after installing MS on server. NEED BE RUN TWICE, THAN GO TO LINK ALLEGRO AND ADD PERMISSION. THAN RUN THIS COMMAND ONCE MORE FOR TAKE ACCESS TOKEN');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var AllegroUserAccounts $appAccount */
        $appAccount = $this->em->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);
        if (!$appAccount) {
            $this->accountService->createAppUser();
        }

        /** @var AllegroTokens $token */
        $token = $appAccount->getAccessAllegroToken();
        if (!$token) {
            /** @var AllegroActivateDevice $device */
            $device = $appAccount->getActiveAllegroDevice();
            if (!$device) {
                $contents = $this->authorization->getNewAllegroDeviceCode();
                $this->authorization->createNewDeviceCode($contents, $appAccount);
                $io->note('Now You get device code, please run this command once more');
            } elseif ($device->getStatus() === 'active') {
                $io->note('Active this link please ' . $device->getVarificationUriComplited());
                $contentForToken = $this->authorization->getAllegroTokenForDevice($device->getDeviceCode());
                $this->authorization->createNewToken($contentForToken, $appAccount);
            }
        } else {
            $io->note('Account have access token');
        }

        $io->success('Finished');
    }
}
