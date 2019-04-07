<?php

namespace App\Services\Allegro;

use App\Controller\Request\RequestController;
use App\Entity\AllegroCategories;
use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\OperationLogger;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryElements
 * @package App\Services\Allegro
 */
class CategoryElements extends MainAppService
{
    const LOG_OP_DOWNLOAD_ACTUAL_CATEGORIES = 'save_actual_categories';
    const LOG_OP_ACTUALIZE_CATEGORIES = 'actualize_categories';

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

    /**
     * @param AllegroUserAccounts $user
     * @param string $category
     * @return object|null
     */
    public function getCategories(AllegroUserAccounts $user, $category = '')
    {
        /** @var AllegroTokens $activeToken */
        $activeToken = $user->getAccessAllegroToken();

        $client = new RequestController();
        $response = $client->createClientRequest()->get(getenv('ALLEGRO_API_SANDBOX') . '/sale/categories?parent.id=' . $category, [
            'headers' => $client->getSimpleAuth($activeToken),
        ]);
// TODO try catch
        $contents = json_decode($response->getBody()->getContents());

        return $contents->categories;
    }

    /**
     * @return AllegroCategories
     * @param object $category
     * @param string|null $parentCategory
     */
    public function saveOneCategory($category, $parentCategory = null)
    {
        $writeCategory = (new AllegroCategories())
            ->setAllegroId(str_replace(" ", "", $category->id))
            ->setAllegroName($category->name)
            ->setLeaf($category->leaf)
            ->setAdvertisement($category->options->advertisement)
            ->setAllegroParentCategory($parentCategory)
            ->setAdvertisementPriceOptional($category->options->advertisementPriceOptional)
            ->setCreateAt(Carbon::now());

        $this->entityManager->persist($writeCategory);
        $this->entityManager->flush();

        return $writeCategory;
    }

    /**
     * @param string $categoryId
     * @param string|null $parentCategory
     * @param AllegroUserAccounts $user
     */
    public function createCategories($categoryId = '', $parentCategory = null, AllegroUserAccounts $user)
    {
        $mainCategories = $this->getCategories($user, $categoryId);

        foreach ($mainCategories as $mainCategory) {
            $childCategory = $this->saveOneCategory($mainCategory, $parentCategory);
            if ($mainCategory->leaf == false) {
                $this->createCategories($mainCategory->id, $childCategory, $user);
            }
        }

        $parentCategory = null;
//        $this->logger->logSimpleOperation(self::LOG_OP_DOWNLOAD_ACTUAL_CATEGORIES);
    }

    /**
     * @param SymfonyStyle $io
     * @param string|null $parentCategory
     * @return boolean
     */
    public function rewriteCategories(SymfonyStyle $io, $parentCategory = null): bool
    {
        $changes = false;

        set_time_limit(0);
        /** @var AllegroUserAccounts $user */
        $user = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);
        if (!$user) {
            $io->error('You not register microservice in Allegro.');
            die;
        }

        $mainCategories = $this->getCategories($user, $parentCategory);

        foreach ($mainCategories as $mainCategory) {
            $resultChange = $this->checkAndChangeOneCategory($mainCategory, $io);
            if($resultChange){
                $changes = true;
            }
            if ($mainCategory->leaf == false) {
                $this->rewriteCategories($io, $mainCategory->id);
            }
        }

        return $changes;
    }

    /**
     * @param SymfonyStyle $io
     * @param $mainCategory
     * @param $parentCategory
     * @return boolean
     */
    public function checkAndChangeOneCategory($mainCategory, SymfonyStyle $io, $parentCategory = null): bool
    {
        $change = false;
        $allegroId = $mainCategory->id;
        /** @var AllegroUserAccounts $user */
        $user = $this->entityManager->getRepository(AllegroUserAccounts::class)->findOneBy(['name' => 'Contelizer']);

        /** @var AllegroCategories $category */
        $category = $this->entityManager->getRepository(AllegroCategories::class)->findOneBy(['allegroId' => $allegroId]);
        if (!$category || $category->getLeaf() !== $mainCategory->leaf) {
            $childCategory = $this->saveOneCategory($mainCategory, $parentCategory);
            if ($mainCategory->leaf == false) {
                $this->createCategories($mainCategory->id, $childCategory, $user);
            }
            $io->note(sprintf('Category ' . strtoupper($mainCategory->name) . ' was wrote'));
            $change = true;
        } else {
            if ($category->getAllegroName() !== $mainCategory->name) {
                $category->setAllegroName($mainCategory->name);
                $io->note(sprintf('AllegroName in category ' . strtoupper($mainCategory->name) . ' changed'));
                $change = true;
            }
            if ($category->getAdvertisement() !== $mainCategory->options->advertisement) {
                $category->setAdvertisement($mainCategory->options->advertisement);
                $io->note(sprintf('Advertisement in category ' . strtoupper($mainCategory->name) . ' changed'));
                $change = true;
            }
            if ($category->getAdvertisementPriceOptional() !== $mainCategory->options->advertisementPriceOptional) {
                $category->setAdvertisementPriceOptional($mainCategory->options->advertisementPriceOptional);
                $io->note(sprintf('AdvertisementPriceOptional in category ' . strtoupper($mainCategory->name) . ' changed'));
                $change = true;
            }
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }
        return $change;
    }
}
