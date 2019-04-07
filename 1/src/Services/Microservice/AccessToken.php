<?php

namespace App\Services\Microservice;

use App\Entity\MicroserviceToken;
use App\Entity\AllegroUserAccounts;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class AccessToken
{
    private $entityManager;

    /**
     * CategoryElements constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(AllegroUserAccounts $user)
    {

        $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $user->getName());
        $newToken = (new MicroserviceToken())
            ->setCreateAt(Carbon::now())
            ->setAccessToken($uuid->toString())
            ->setFinishTo(Carbon::now()->addMonth(10))
            ->setPriority('normal')
            ->setStatus('activated')
            ->setUserAllegro($user);

        $this->entityManager->persist($newToken);
        $this->entityManager->flush();

        return $newToken;
    }
}