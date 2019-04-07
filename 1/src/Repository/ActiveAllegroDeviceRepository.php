<?php

namespace App\Repository;

use App\Entity\AllegroActivateDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllegroActivateDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroActivateDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroActivateDevice[]    findAll()
 * @method AllegroActivateDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiveAllegroDeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllegroActivateDevice::class);
    }

    // /**
    //  * @return AllegroActivateDevice[] Returns an array of AllegroActivateDevice objects
    //  */

    public function findDeviceByUserId($id): ?AllegroActivateDevice
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }


//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(1)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    /*
    public function findOneBySomeField($value): ?AllegroActivateDevice
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
