<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProduitPanier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'display')]
    public function displayPanier(Request $request): Response
    {
        $panier = [];
        if (!is_null($request->getSession()->get('panier'))) {
            $panier = $request->getSession()->get('panier');
        }
        $prixTotal = 0;
        $nbArticles = 0;

        foreach ($panier as $item) {
            $prixTotal += $item->getProduit()->getPrixHt() * $item->getQuantite();
            $nbArticles += $item->getQuantite();
        }

        $request->getSession()->set(('nbArticles'), $nbArticles);

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'prixTotal' => $prixTotal,
            'nbArticles' => $nbArticles,
        ]);
    }

    /*************** FONCTION AJOUT DU PANIER ***************/
    private function ajoutPanier($produit, $request, $quantity)
    {

        $produitPanier = new ProduitPanier();
        $produitPanier->setProduit($produit);
        $produitPanier->setQuantite($quantity);

        $session = $request->getSession();

        $panier = [];
        if ($session->has('panier')) {
            $panier = $session->get('panier');


        }

        $exist = false;


        /** Ajustement de la quantité lors d'ajout d'un produit déjà existant **/
        foreach ($panier as $item) {
            if ($item->getProduit()->getId() == $produit->getId()) {
                $exist = true;
                $item->setQuantite($item->getQuantite() + $quantity);
            }

        }


        if (!$exist) {

            $panier[] = $produitPanier;
        }

        $nbArticles = 0;
        /** Compte du nombre d'articles totaux dans les paniers **/
        foreach ($panier as $item) {
            $nbArticles += $item->getQuantite();
        }

        /** Enregistrement du nombre de paniers en session **/
        $session->set(('nbArticles'), $nbArticles);
        $session->set('panier', $panier);


    }

    /***************FIN FONCTION AJOUT DU PANIER***************/

    #[Route('/add/{id}/{qty}', name: 'add_stay', requirements: ['id' => '\d+', 'qty' => '\d+'])]
    public function addPanierStay(Produit $produit, Request $request, $qty): Response
    {


        $this->ajoutPanier($produit, $request, $qty);
        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/{id}/{qty}', name: 'add', requirements: ['id' => '\d+', 'qty' => '\d+'])]
    public function addPanier(Produit $produit, Request $request, $qty): Response
    {

        $this->ajoutPanier($produit, $request, $qty);

        return $this->redirectToRoute('panier_display');
    }

    #[Route('/remove/{id}', name: 'remove_product')]
    public function removeProduit(Produit $produit, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');

        $delete = null;
        foreach ($panier as $key => $item) {
            if ($produit->getId() == $item->getProduit()->getId()) {
                $delete = $key;
            }
        }

        unset($panier[$delete]);

        $session->set('panier', $panier);


        return $this->redirectToRoute('panier_display');
    }

    #[Route('/{operator}/{id}', 'product_quantity')]
    public function QuantitePanierProduit(Produit $produit, Request $request, $operator)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');

        foreach ($panier as $item) {
            if ($item->getProduit()->getId() == $produit->getId()) {
                if ($operator == 'plus') {
                    $item->setQuantite($item->getQuantite() + 1);
                } elseif ($operator == 'moins') {
                    if ($item->getQuantite() == 0) {
                        $item->setQuantite(0);
                    } else {
                        $item->setQuantite($item->getQuantite() - 1);
                    }

                }

            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_display');
    }


}
