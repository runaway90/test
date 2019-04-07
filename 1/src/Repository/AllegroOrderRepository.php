<?php

namespace App\Repository;

use App\Entity\AllegroOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroOrder[]    findAll()
 * @method AllegroOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroOrder::class);
    }

    // /**
    //  * @return AllegroOrder[] Returns an array of AllegroOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AllegroOrder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
