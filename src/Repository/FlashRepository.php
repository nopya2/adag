<?php

namespace App\Repository;

use App\Entity\Flash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Flash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flash[]    findAll()
 * @method Flash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Flash::class);
    }

    public function find5lastFlashes(){
        return $this->createQueryBuilder('f')
            // ->andWhere('b.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('f.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Flash[] Returns an array of Flash objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Flash
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
