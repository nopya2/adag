<?php

namespace App\Repository;

use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Partenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partenaire[]    findAll()
 * @method Partenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartenaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }

//    /**
//     * @return Partenaire[] Returns an array of Partenaire objects
//     */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Partenaire
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //Requete DQL
    public function findAllBySearch($search): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Partenaire p
            WHERE p.organisation LIKE :search
            OR p.login LIKE :search
            ORDER BY p.id DESC'
        )->setParameter('search', '%'.$search.'%');

        // returns an array of Product objects
        return $query->execute();
    }

    // Requete SQL
    // public function findAllGreaterThanPrice($price): array
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT * FROM product p
    //         WHERE p.price > :price
    //         ORDER BY p.price ASC
    //         ';
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute(['price' => 1000]);

    //     // returns an array of arrays (i.e. a raw data set)
    //     return $stmt->fetchAll();
    // }
}
