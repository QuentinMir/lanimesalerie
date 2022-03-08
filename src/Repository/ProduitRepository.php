<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findByPagination(int $pageCourante, int $nbResultats)
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults($nbResultats)
            ->setFirstResult($pageCourante * $nbResultats - $nbResultats)
            ->getQuery()->getResult();
    }

    public function search($filters, int $pageCourante, int $nbResultats, $ordre)
    {
        $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque');

        if (!empty($filters)) {
            if (!is_null($filters['ordre'])) {
                $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->orderBy('p.prixHt', $ordre);
            }

            $this->ifFilters($filters, $query);

        }

        $results = [];
        // le nombre de produits de cette query
        $nbProduits = $query->getQuery()->getResult();
        // les produits de la query
        $produits = $query->setMaxResults($nbResultats)
            ->setFirstResult($pageCourante * $nbResultats - $nbResultats)
            ->getQuery()
            ->getResult();


        array_push($results, $nbProduits, $produits);
        return $results;

    }

    public function searchCat($filters, int $pageCourante, int $nbResultats, $ordre, $categorie)
    {
        $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie);

        if (!empty($filters)) {
            if (!is_null($filters['ordre'])) {
                $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie)->orderBy('p.prixHt', $ordre);
            }

            $this->ifFilters($filters, $query);

        }

        $results = [];
        // le nombre de produits de cette query
        $nbProduits = $query->getQuery()->getResult();
        // les produits de la query
        $produits = $query->setMaxResults($nbResultats)
            ->setFirstResult($pageCourante * $nbResultats - $nbResultats)
            ->getQuery()
            ->getResult();


        array_push($results, $nbProduits, $produits);
        return $results;

    }


    public function searchSouscat($filters, int $pageCourante, int $nbResultats, $ordre, $categorie, $souscategorie)
    {
        $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie)->andWhere('p.idSousCategorie = :souscategorie')->setParameter('souscategorie', $souscategorie);

        if (!empty($filters)) {
            if (!is_null($filters['ordre'])) {
                $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie)->andWhere('p.idSousCategorie = :souscategorie')->setParameter('souscategorie', $souscategorie)->orderBy('p.prixHt', $ordre);
            }

            $this->ifFilters($filters, $query);

        }

        $results = [];
        // le nombre de produits de cette query
        $nbProduits = $query->getQuery()->getResult();
        // les produits de la query
        $produits = $query->setMaxResults($nbResultats)
            ->setFirstResult($pageCourante * $nbResultats - $nbResultats)
            ->getQuery()
            ->getResult();


        array_push($results, $nbProduits, $produits);
        return $results;

    }

    public function searchSubSouscat($filters, int $pageCourante, int $nbResultats, $ordre, $categorie, $souscategorie, $subsouscategorie)
    {
        $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie)->andWhere('p.idSousCategorie = :souscategorie')->setParameter('souscategorie', $souscategorie)->andWhere('p.idSubSousCategorie = :subsouscategorie')->setParameter('subsouscategorie', $subsouscategorie);

        if (!empty($filters)) {
            if (!is_null($filters['ordre'])) {
                $query = $this->createQueryBuilder('p')->leftJoin('p.idMarque', 'marque')->where('p.idCategorie = :categorie')->setParameter('categorie', $categorie)->andWhere('p.idSousCategorie = :souscategorie')->setParameter('souscategorie', $souscategorie)->andWhere('p.idSubSousCategorie = :subsouscategorie')->setParameter('subsouscategorie', $subsouscategorie)->orderBy('p.prixHt', $ordre);
            }

            $this->ifFilters($filters, $query);

        }

        $results = [];
        // le nombre de produits de cette query
        $nbProduits = $query->getQuery()->getResult();
        // les produits de la query
        $produits = $query->setMaxResults($nbResultats)
            ->setFirstResult($pageCourante * $nbResultats - $nbResultats)
            ->getQuery()
            ->getResult();


        array_push($results, $nbProduits, $produits);
        return $results;

    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param $filters
     * @param \Doctrine\ORM\QueryBuilder $query
     * @return void
     */
    public function ifFilters($filters, \Doctrine\ORM\QueryBuilder $query): void
    {
        if (!is_null($filters['searchBar'])) {
            $query->where('p.nom LIKE :nom')
                ->orWhere('p.description LIKE :nom')
                ->orWhere('marque.nom LIKE :nom')
                ->setParameter('nom', '%' . $filters['searchBar'] . '%');
        }

        if (!is_null($filters['marque'])) {
            $query->andWhere('marque = :marque')
                ->setParameter('marque', $filters['marque']);
        }

        if (!is_null($filters['prixMin'])) {
            $query->andWhere('p.prixHt > :prixMin')
                ->setParameter('prixMin', $filters['prixMin']);
        }

        if (!is_null($filters['prixMax'])) {
            $query->andWhere('p.prixHt < :prixMax')
                ->setParameter('prixMax', $filters['prixMax']);
        }
    }


}
