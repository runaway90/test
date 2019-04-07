<?php

namespace App\Services\Microservice;

use App\Entity\AllegroUserAccounts;
use App\Entity\MicroserviceOperationLogs;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class OperationLogger
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

    public function logSimpleOperation(
        $operationName,
        $appLevel = MicroserviceOperationLogs::APP_LEVEL_DEFAULT,
        $description = null,
        AllegroUserAccounts $allegroAccount = null)
    {
        $log = (new MicroserviceOperationLogs())
            ->setName($operationName)
            ->setDescription($description)
            ->setTime(Carbon::now())
            ->setAlert(false)
            ->setPriority(0)
            ->setAppLevel($appLevel)
            ->setAllegroAccount($allegroAccount);
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function changeDescription(MicroserviceOperationLogs $log, $description)
    {
        $log->setDescription($description);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function markerAlert(MicroserviceOperationLogs $log)
    {
        $log->setAlert(true);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function prioritization(MicroserviceOperationLogs $log, $newPriority)
    {
        $log->setPriority($newPriority);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

//    public function save(MicroserviceOperationLogs $log)
//    {
//        $this->entityManager->persist($log);
//        $this->entityManager->flush();
//    }

}

