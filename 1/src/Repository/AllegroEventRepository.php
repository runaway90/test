<?php

namespace App\Repository;

use App\Entity\AllegroEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroEvent[]    findAll()
 * @method AllegroEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroEvent::class);
    }

    // /**
    //  * @return AllegroEvent[] Returns an array of AllegroEvent objects
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
    public function findOneBySomeField($value): ?AllegroEvent
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
