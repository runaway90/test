<?php

namespace App\Repository;

use App\Entity\AllegroParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroParameter[]    findAll()
 * @method AllegroParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroParameterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroParameter::class);
    }

    // /**
    //  * @return AllegroParameter[] Returns an array of AllegroParameter objects
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
    public function findOneBySomeField($value): ?AllegroParameter
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
