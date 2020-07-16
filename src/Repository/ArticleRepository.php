<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findArticles($search){
        return $this->createQueryBuilder('a')
            // ->andWhere('b.exampleField = :val')
            ->where('a.titre LIKE :titre')
                ->setParameter('titre', '%'.$search.'%')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
//            ->getResult()
            ;
    }

    public function findlastArticles($limit){
        return $this->createQueryBuilder('a')
            // ->andWhere('b.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    //Les resultats des articles par rubrique sans pagination
    public function findlastArticlesByRubrique($rubrique, $limit){
        return $this->createQueryBuilder('a')
            ->leftJoin('a.rubrique', 'r')
            ->where('r.id = :rubrique')
                ->setParameter('rubrique', $rubrique)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    //Les resultats des articles par rubrique avec pagination
    public function querylastArticlesByRubrique($rubrique){
        return $this->createQueryBuilder('a')
            ->leftJoin('a.rubrique', 'r')
            ->where('r.id = :rubrique')
            ->setParameter('rubrique', $rubrique)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ;
    }

    public function findMostPopular($limit){
        return $this->createQueryBuilder('a')
            ->select('a AS article')
            ->leftJoin('a.vues', 'v')
            ->addSelect('COUNT(v.id) AS vues')
            // ->andWhere('b.exampleField = :val')
            // ->setParameter('val', $value)
//            ->orderBy('a.vues', 'DESC')
            ->groupBy('a.id')
            ->orderBy('vues', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
    
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('a.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }


    

    /*
    public function findOneBySomeField($value): ?Article
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
