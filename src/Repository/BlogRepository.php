<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findRecentsPosts($limit)
    {
        return $this->createQueryBuilder('b')
            // ->andWhere('b.exampleField = :val')
            ->andWhere('b.visible = true')
            // ->setParameter('val', $value)
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findVisibleBlogs()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.visible = :visible')
            ->setParameter('visible', true)
            ->orderBy('b.createdAt', 'DESC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPreviousBlog($blogId)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.visible = :visible')
                ->setParameter('visible', true)
            ->andWhere('b.id < :blogId')
                ->setParameter('blogId', $blogId)
            ->orderBy('b.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getNextBlog($blogId)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.visible = :visible')
                ->setParameter('visible', true)
            ->andWhere('b.id > :blogId')
                ->setParameter('blogId', $blogId)
            ->orderBy('b.id', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(1)

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
