<?php


namespace App\Repository;

use App\Entity\Subsouscategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subsouscategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subsouscategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subsouscategorie[]    findAll()
 * @method Subsouscategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubsouscategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subsouscategorie::class);
    }

    // /**
    //  * @return Subsouscategorie[] Returns an array of Subsouscategorie objects
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
    public function findOneBySomeField($value): ?Subsouscategorie
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
