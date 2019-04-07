<?php

namespace App\Repository;

use App\Entity\AllegroPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroPhoto[]    findAll()
 * @method AllegroPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroPhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroPhoto::class);
    }

    // /**
    //  * @return AllegroPhoto[] Returns an array of AllegroPhoto objects
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
    public function findOneBySomeField($value): ?AllegroPhoto
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
