<?php

namespace App\Command;

use App\Entity\AllegroTokens;
use App\Entity\AllegroUserAccounts;
use App\Services\Allegro\AuthorizationProcess;
use App\Services\Allegro\CategoryElements;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AllegroRefreshMicroserviceTokenCommand extends Command
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

    protected static $defaultName = 'allegro:refresh-microservice-token';

    protected function configure()
    {
        $this
            ->setDescription('Refresh all Allegro user token');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->em->getRepository(AllegroUserAccounts::class)->findAll();
        if (!$users) {
            $io->error('You haven`t anyone user in microservice');

        }

        /** @var AllegroUserAccounts $user */
        foreach ($users as $user) {
            /** @var AllegroTokens $getToken */
            $getToken = $user->getAccessAllegroToken();
            if($getToken){
                $contentForToken = $this->authorization->getAllegroRefreshToken($getToken);

                /** @var AllegroTokens $token */
                $this->authorization->refreshToken($contentForToken, $getToken);
            }
        }
        $io->success('All tokens was refreshed');
    }
}
