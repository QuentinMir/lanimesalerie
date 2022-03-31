<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\ProduitPanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class DataController extends AbstractController
{
    #[Route('/data', name: 'data', methods: ['GET'])]
    public function sendData(EntityManagerInterface $entityManager, ProduitPanierRepository $pr): JsonResponse
    {
        $commandes = $entityManager->getRepository(Commande::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();
        $nouveauClient = 0;
        $dejaClient = 0;

        foreach ($users as $user) {
            if (count($user->getCommandes()) > 1) {
                $dejaClient++;
            } elseif (count($user->getCommandes()) == 1) {
                $nouveauClient++;
            }

        }
        $totalSales = 0;

        $rands1 = [];
        for ($i = 0; $i < 12; $i++) {
            $rands1[] = rand(100, 200);
        }
        $rands2 = [];
        for ($i = 0; $i < 12; $i++) {
            $rands2[] = rand(5, 30);
        }

        foreach ($commandes as $commande) {
            foreach ($commande->getPaniers() as $panier) {
                /** Ventes totales **/
                $totalSales += ($panier->getProduit()->getPrixHt()) * ($panier->getQuantite());
            }
        }

        /** Panier moyen **/
        $avgCart = round($totalSales / count($commandes), 2);

        $allProduits = $entityManager->getRepository(Produit::class)->findAll();

        /** récupération des 12 produits les plus vendus **/
        $produitsOrdonnes = $pr->getAllProduitsVentesDesc();

        $nomProduits = [];
        $catProduits = [];
        $nbOfUnits = [];
        foreach ($produitsOrdonnes as $produitOrdonne) {

            /** récupération des nombre de vente **/
            $nbOfUnits[] = $produitOrdonne['compte'];
            /** récupération du nom et de la catégorie des produits **/
            foreach ($allProduits as $produit) {
                if ($produitOrdonne['id'] == $produit->getId()) {
                    $nomProduits[] = $produit->getNom();
                    $catProduits[] = $produit->getIdCategorie()->getNom();
                }
            }
        }

        /** Retourner les datas jsonresponse **/
        return new JsonResponse([
            'totalSales' => $totalSales,
            'totalCarts' => count($commandes),
            'totalOrders' => count($commandes),
            'avgCart' => $avgCart,
            'newClient' => $nouveauClient,
            'existingClient' => $dejaClient,
            'visits' => $rands1,
            'createdCarts' => $rands2,
            'productName' => $nomProduits,
            'nbOfUnits' => $nbOfUnits,
            'categoryName' => $catProduits,
        ]);
    }
}
