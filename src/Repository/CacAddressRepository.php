<?php

namespace App\Repository;

use App\Entity\CacAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CacAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CacAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CacAddress[]    findAll()
 * @method CacAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CacAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CacAddress::class);
    }

    // /**
    //  * @return CacAddress[] Returns an array of CacAddress objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CacAddress
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
