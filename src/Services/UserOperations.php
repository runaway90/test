<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserOperations
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

    public function saveNewUser($requestBody, $uuid): User
    {
        $newUser = (new User())
            ->setUuid($uuid)
            ->setAddres($requestBody->addres)
            ->setEmail($requestBody->email)
            ->setName($requestBody->name)
            ->setSurname($requestBody->surname);

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return $newUser;
    }

    public function checkBodyStructureForCreateUser($requestBody)
    {
        switch ($requestBody) {
            case (!$requestBody->name || count($requestBody->name) < 3 || count($requestBody->name) > 255):
                return 'Please check name of user[it must to be more 2 and less than 256 liters]';

            case (!$requestBody->surname || count($requestBody->surname) <= 3 || count($requestBody->surname) > 255):
                return'Please check name of user[it must to be more 3 and less than 256 liters]';

            case (!$requestBody->email || count($requestBody->email) <= 7 || count($requestBody->email) > 255):
                return 'Please check name of user[it must to be more 7 and less than 256 liters]';

            case (!$requestBody->addres || count($requestBody->addres) <= 10 || count($requestBody->addres) > 255):
                return 'Please check name of user[it must to be more 10 and less than 256 liters]';

            case (!$requestBody->uuid || count($requestBody->uuid) <= 3 || count($requestBody->uuid) > 255):
                return 'Please check name of user[it must to be more 3 and less than 256 liters]';
        }

        return null;

    }
}