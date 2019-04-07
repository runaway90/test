<?php

namespace App\Command;

use App\Entity\MicroserviceApplication;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MsShowAllAppSecretCommand extends Command
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

    protected static $defaultName = 'ms:show-all-app-secret';

    protected function configure()
    {
        $this->setDescription('Show all application secret id`s and key`s in MS');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $apps = $this->em->getRepository(MicroserviceApplication::class)->findAll();
        foreach ($apps as $app) {
            $io->note('Application name - ' . $app->getName() . '. Secret_id - ' . $app->getAppId() . '. Secret_key - ' . $app->getAppSecret());
        }
        $io->success('Finished');
    }
}
