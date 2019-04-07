<?php

namespace App\Repository;

use App\Entity\AllegroParameterDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroParameterDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroParameterDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroParameterDictionary[]    findAll()
 * @method AllegroParameterDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroParameterDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroParameterDictionary::class);
    }

    // /**
    //  * @return AllegroParameterDictionary[] Returns an array of AllegroParameterDictionary objects
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
    public function findOneBySomeField($value): ?AllegroParameterDictionary
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
