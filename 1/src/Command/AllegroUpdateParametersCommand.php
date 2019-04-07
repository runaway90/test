<?php

namespace App\Command;

use App\Services\Allegro\CategoryElements;
use App\Services\Allegro\ParameterElements;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AllegroUpdateParametersCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ParameterElements
     */
    private $parameterElements;

    /**
     * @var OperationLogger
     */
    private $logger;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param ParameterElements $parameterElements
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $em, ParameterElements $parameterElements, OperationLogger $logger)
    {
        $this->em = $em;
        $this->parameterElements = $parameterElements;
        $this->logger = $logger;
        parent::__construct();
    }

    protected static $defaultName = 'allegro:update-parameters';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('force')) {

            $response = $this->parameterElements->checkAndUpdateParameters();
            if($response){
                $io->success('Parameters was checked and updated');
            }else{
                $io->success('Parameters are actual');
            }

        }

    }
}
