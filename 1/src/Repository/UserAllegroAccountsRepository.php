<?php

namespace App\Repository;

use App\Entity\AllegroUserAccounts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroUserAccounts|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroUserAccounts|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroUserAccounts[]    findAll()
 * @method AllegroUserAccounts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAllegroAccountsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroUserAccounts::class);
    }

    public function findUserAccountById($id): ?AllegroUserAccounts
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
