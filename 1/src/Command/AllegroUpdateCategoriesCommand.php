<?php

namespace App\Command;

use App\Entity\AllegroCategories;
use App\Entity\MicroserviceOperationLogs;
use App\Services\Allegro\CategoryElements as CategoryAllegro;
use App\Services\Microservice\CategoriesOperations as CategoryMicroservice;
use App\Services\Microservice\CoreRequest;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AllegroUpdateCategoriesCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryAllegro
     */
    private $categoryAllegroElements;

    /**
     * @var OperationLogger
     */
    private $logger;

    /**
     * @var CategoryMicroservice
     */
    private $categoriesMicroserviceOperations;

    /**
     * @var CoreRequest
     */
    private $requestToCore;

    /**
     * AllegroGetActualCategoriesCommand constructor.
     * @param EntityManagerInterface $em
     * @param CategoryAllegro $categoryAllegroElements
     * @param CategoryMicroservice $categoriesMicroserviceOperations
     * @param OperationLogger $logger
     * @param CoreRequest $coreRequest
     */
    public function __construct(EntityManagerInterface $em, CategoryAllegro $categoryAllegroElements, OperationLogger $logger, CategoryMicroservice $categoriesMicroserviceOperations, CoreRequest $coreRequest)
    {
        $this->em = $em;
        $this->categoryAllegroElements = $categoryAllegroElements;
        $this->logger = $logger;
        $this->categoriesMicroserviceOperations = $categoriesMicroserviceOperations;
        $this->requestToCore = $coreRequest;
        parent::__construct();
    }

    protected static $defaultName = 'allegro:update-categories';

    protected function configure()
    {
        $this
            ->setDescription('Actualization all categories')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Rewrite all categories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('force')) {
            $changes = $this->categoryAllegroElements->rewriteCategories($io);
            if ($changes) {
                $this->rewriteFileWithCategories();
                $this->requestToCore->sendRequestToCore();
            }
            $io->success('Categories rewrote');

            $this->logger->logSimpleOperation(
                CategoryAllegro::LOG_OP_ACTUALIZE_CATEGORIES,
                MicroserviceOperationLogs::APP_LEVEL_ALLEGRO_CLIENT,
                get_called_class());

        } else {
            $io->note('If You want update categories, please run this command with --force option');
        }

    }

    protected function rewriteFileWithCategories()
    {
        $categories = $this->em->getRepository(AllegroCategories::class)->findAll();
        /** @var AllegroCategories $category */
        foreach ($categories as $category) {
            $parent = $this->categoriesMicroserviceOperations->getPathOfCategory($category);
            if ($category->getLeaf()) {
                $categoryData[$category->getAllegroId()] = $parent;
            }
        }

        $json_data = json_encode($categoryData);
        file_put_contents(__DIR__ . '/../../public/categories.json', $json_data, true);

        $this->logger->logSimpleOperation(
            CategoryMicroservice::REWROTE_CATEGORIES_FILE,
            MicroserviceOperationLogs::APP_LEVEL_MS_API,
            get_called_class());
    }

}
