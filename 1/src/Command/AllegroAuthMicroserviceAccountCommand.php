<?php

namespace App\Command;

use App\Services\Allegro\AuthorizationProcess;
use App\Services\Allegro\CategoryElements;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AllegroAuthMicroserviceAccountCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryElements
     */
    private $category;

    /**
     * @var AuthorizationProcess
     */
    private $authorization;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param CategoryElements $category
     * @param AuthorizationProcess $authorization
     */
    public function __construct(EntityManagerInterface $em, CategoryElements $category, AuthorizationProcess $authorization)
    {
        $this->em = $em;
        $this->category = $category;
        $this->authorization = $authorization;
        parent::__construct();
    }

    protected static $defaultName = 'allegro:auth-microservice-account';

    protected function configure()
    {
        $this
            ->setDescription('Not work now')
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }

        $io->success('Not work now');
    }
}
