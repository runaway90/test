<?php

namespace App\Services;

use App\Entity\Package;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PackageOperations
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * CategoryElements constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveNewPackage($requestBody, User $user): Package
    {
        $newPackage = (new Package())
            ->setCreateDate(new \DateTime())
            ->setStatus(Package::PACKAGE_STATUS_CREATED)
            ->setUser($user)
            ->setAddresFrom($requestBody->address_from)
            ->setAddresTo($requestBody->address_to);

        $this->entityManager->persist($newPackage);
        $this->entityManager->flush();

        return $newPackage;
    }

    public function checkBodyStructureForCreatePackage($requestBody)
    {
        switch ($requestBody) {
            case (!$requestBody->address_from || count($requestBody->address_from) < 3 || count($requestBody->address_from) > 255):
                return 'Please check address_from of user[it must to be more 2 and less than 256 liters]';

            case (!$requestBody->address_to || count($requestBody->address_to) <= 3 || count($requestBody->address_to) > 255):
                return 'Please check address_to of user[it must to be more 3 and less than 256 liters]';
        }

        return null;

    }

    public function findPackageByUUID(Request $request)
    {
        $requestBody = json_decode($request->getContent());
        return $this->entityManager->getRepository(Package::class)->findOneBy(['uuid' => $requestBody]);
    }

    public function setStatus(Package $package, $status)
    {
        $package->setStatus($status);

        $this->entityManager->persist($package);
        $this->entityManager->flush();

        return $package;
    }

}