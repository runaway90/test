<?php

namespace App\Repository;

use App\Entity\AllegroOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroOffer[]    findAll()
 * @method AllegroOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroOffer::class);
    }

    // /**
    //  * @return AllegroOffer[] Returns an array of AllegroOffer objects
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
    public function findOneBySomeField($value): ?AllegroOffer
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
