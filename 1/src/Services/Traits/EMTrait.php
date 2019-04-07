<?php
namespace App\Services\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

trait EMTrait
{
    /**
     * @param string $className
     * @return ObjectRepository
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function getRepository(string $className): ObjectRepository
    {
        return $this->em->getRepository($className);
    }

    /**
     * @return EntityManagerInterface
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function getEM(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * Function for saving array of objects with using Doctrine
     * @param array|ArrayCollection $objects
     * @param int $flushOnEvery
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function saveObjects($objects, int $flushOnEvery = 10)
    {
        $itemsCount = 1;
        foreach ($objects as $object) {
            $this->getEM()->persist($object);
            if ($itemsCount % $flushOnEvery === 0) {
                $this->getEM()->flush();
            }
            $itemsCount++;
        }
        $this->getEM()->flush();
    }

    /**
     * Function for save object with using Doctrine
     * @param object $object
     * @param bool $flush
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function saveObject($object, bool $flush = true)
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Function for remove object with using Doctrine
     * @param object $object
     * @param bool $flush
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function removeObject($object, bool $flush = true)
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Function for removing array of objects with using Doctrine
     * @param array|ArrayCollection $objects
     * @param int $flushOnEvery
     * @author Michał Szargut <michal.szargut@contelizer.pl>
     */
    protected function removeObjects($objects, int $flushOnEvery = 10)
    {
        $itemsCount = 1;
        foreach ($objects as $object) {
            $this->getEM()->remove($object);
            if ($itemsCount % $flushOnEvery === 0) {
                $this->getEM()->flush();
            }
            $itemsCount++;
        }
        $this->getEM()->flush();
    }
}