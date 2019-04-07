<?php

namespace App\Repository;

use App\Entity\AllegroTokens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroTokens|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroTokens|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroTokens[]    findAll()
 * @method AllegroTokens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroAccessTokensRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroTokens::class);
    }

    // /**
    //  * @return AllegroTokens[] Returns an array of AllegroTokens objects
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
    public function findOneBySomeField($value): ?AllegroTokens
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
