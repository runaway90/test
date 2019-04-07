<?php

namespace App\Command;

use App\Entity\AllegroCategories;
use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\CategoryElements;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AllegroGetActualCategoriesCommand
 * @package App\Command
 */
class AllegroGetActualCategoriesCommand extends Command
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
     * @var OperationLogger
     */
    private $logger;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param CategoryElements $category
     * @param OperationLogger $logger
     */
    public function __construct(EntityManagerInterface $em, CategoryElements $category, OperationLogger $logger)
    {
        $this->em = $em;
        $this->category = $category;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * @var string
     */
    protected static $defaultName = 'allegro:get-actual-categories';

    protected function configure()
    {
        $this->setDescription('Take all actual Allegro categories and write to DB')
            ->addOption('save', null, InputOption::VALUE_NONE, 'Save all categories to DB in table "allegro_categories"');
//            ->addArgument('user', null, InputArgument::OPTIONAL, null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

//        $userId = $input->getArgument('user');
//        if (!$userId) {
//            $io->error('Please check user id');
//            die;
//        }

        $category = $this->em->getRepository(AllegroCategories::class)->findAll();
        if (!$category) {
            if ($input->getOption('save')) {
                $this->writeActualCategories($io);
                $io->success('Categories wrote to DB');
            } else {
                $io->error('For saving categories please add option --save');
            }
        } else {
            $io->error('Categories already wrote to DB');
        }
    }

    /**
     * @param SymfonyStyle $io
     * @param $userId
     */
    public function writeActualCategories($io)
    {
        set_time_limit(0);
        $parentCategory = null;

        /** @var AllegroUserAccounts $user */
        $user = $this->em->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);
        if (!$user) {
            $io->error('User id not found');
            die;
        }

        $mainCategories = $this->category->getCategories($user);

        if ($mainCategories == null) {
            $io->error('User id not found');
            die;
        }

        foreach ($mainCategories as $mainCategory) {
            $childCategory = $this->category->saveOneCategory($mainCategory, $parentCategory);
            if ($mainCategory->leaf == false) {
                $this->category->createCategories($mainCategory->id, $childCategory, $user);
            }
            $io->note(sprintf('Category ' . strtoupper($mainCategory->name) . ' was wrote'));

        }

        $this->logger->logSimpleOperation(
            CategoryElements::LOG_OP_DOWNLOAD_ACTUAL_CATEGORIES,
            MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
            get_called_class(),
            $user);
    }
}
