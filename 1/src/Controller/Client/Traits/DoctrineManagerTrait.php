<?php

namespace App\Controller\Client\Traits;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

trait DoctrineManagerTrait
{
    protected function getRepository(string $repository): ServiceEntityRepositoryInterface
    {
        return $this->getDoctrine()->getRepository($repository);
    }
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->getDoctrine()->getManager();
    }

    protected function saveObject(&$object, $flush = true):void
    {
        $this->getEntityManager()->persist($object);

        if ($flush){
            $this->getEntityManager()->flush();
        }
    }
}