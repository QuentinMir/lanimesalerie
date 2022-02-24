<?php


namespace App\Repository;

use App\Entity\Souscategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Souscategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Souscategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Souscategorie[]    findAll()
 * @method Souscategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SouscategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Souscategorie::class);
    }

    // /**
    //  * @return Souscategorie[] Returns an array of Souscategorie objects
    //  */
    /*
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
    */

    /*
    public function findOneBySomeField($value): ?Souscategorie
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
