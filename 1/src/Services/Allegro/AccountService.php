<?php

namespace App\Services\Allegro;

use App\Entity\MicroserviceOperationLogs;
use Ramsey\Uuid\Uuid;
use \Symfony\Component\HttpFoundation\Response;
use App\Entity\AllegroUserAccounts;
use App\Services\Microservice\OperationLogger;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AccountService
 * @package App\Services\Allegro
 */
class AccountService extends MainAppService
{
    const LOG_OP_CREATE_USER = 'create_user';

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
     * @param string $name
     * @throws
     */
    public function createAppUser($name = 'Contelizer')
    {
        /** @var AllegroUserAccounts $user */
        $user = (new AllegroUserAccounts())
            ->setPassword(getenv('ALLEGRO_APP_PASS'))
            ->setLogin(getenv('ALLEGRO_APP_ACCOUNT'))
            ->setName($name)
            ->setUuid(Uuid::uuid4()->toString());
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->logger->logSimpleOperation(self::LOG_OP_CREATE_USER,
            MicroserviceOperationLogs::APP_LEVEL_DEFAULT,
            get_called_class(),
            $user);
//        return new Response('New user with name ' . $user->getName() . ' was created', 200);
    }

//    /**
//     * @param string $name
//     * @return Response
//     */
//    public function createTestUser($name = 'test_user')
//    {
//        /** @var AllegroUserAccounts $user */
//        $user = (new AllegroUserAccounts())
//            ->setName($name)
//            ->setUuid('sfdgh-sdfh');
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//
//        $this->logger->logSimpleOperation(self::LOG_OP_CREATE_USER, MicroserviceOperationLogs::APP_LEVEL_DEFAULT, get_called_class(), $user);
//
//        return new Response('New user with name ' . $user->getName() . ' was created', 200);
//
//    }
}
