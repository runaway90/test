<?php

namespace App\Repository;

use App\Entity\MicroserviceOperationLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MicroserviceOperationLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroserviceOperationLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroserviceOperationLogs[]    findAll()
 * @method MicroserviceOperationLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroserviceOperationLogsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MicroserviceOperationLogs::class);
    }

    // /**
    //  * @return MicroserviceOperationLogs[] Returns an array of MicroserviceOperationLogs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MicroserviceOperationLogs
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
