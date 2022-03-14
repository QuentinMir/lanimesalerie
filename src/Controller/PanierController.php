<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\ProduitPanier;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'display')]
    public function displayPanier(Request $request, SecurityController $sc): Response
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

        $user = $sc->getUser();

        /** début du tunnel d'achat **/
        if ($request->get("panierOK") == true && count($panier) > 0) {
            if ($user == null) {
                // faire redirection si pas connecté
                return $this->redirectToRoute('panier_connexion');
            } else {
                return $this->redirectToRoute('panier_compte');
                // rediriger vers le paiement
            }

        }

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

    #[Route('/connexion', name: 'connexion')]
    public function login(SecurityController $sc, AuthenticationUtils $authenticationUtils, Request $request): Response
    {

        // si connecté est envoyé vers le choix du moyen de paiement
        if ($sc->getUser() != null) {
            return $this->redirectToRoute('panier_paiement');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('panier/connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    #[IsGranted('ROLE_USER')]
    #[Route('/paiement', name: 'paiement')]
    public function paiementPanier(Request $request, SecurityController $sc, EntityManagerInterface $entityManager): Response
    {
        $panier = [];
        $session = $request->getSession();
        if (is_null($session->get('panier'))) {
            return $this->redirectToRoute('default');
        }

        if (!is_null($session->get('panier'))) {
            $panier = $session->get('panier');
        }
        $prixTotal = 0;
        $nbArticles = 0;

        foreach ($panier as $item) {
            $prixTotal += $item->getProduit()->getPrixHt() * $item->getQuantite();
            $nbArticles += $item->getQuantite();
        }


        $user = $sc->getUser();


        $paiement = $request->get('paiement');
        if ($panier > 0 && $paiement == "paypal" || $paiement == 'visa' || $paiement == 'mastercard') {
            $commande = new Commande();
            $commande->setUser($user);
            $commande->setDate(new \DateTime());
            $commande->setEtat('acceptée');
            $commande->setPaiement($paiement);

            $entityManager->persist($commande);


            $produitPanier = new ProduitPanier();
            foreach ($panier as $item) {
                $produit = $item->getProduit();
                $produitPanier->setProduit($produit);
                $produitPanier->setQuantite($item->getQuantite());
                $produitPanier->setCommande($commande);
                $entityManager->merge($produitPanier);
            }

            $entityManager->flush();

            $session->remove('panier');

            return $this->render('panier/synthese.html.twig', [
                'panier' => $panier,
                'prixTotal' => $prixTotal,
                'nbArticles' => $nbArticles,
            ]);


        }


        return $this->render('panier/paiement.html.twig', [
            'panier' => $panier,
            'prixTotal' => $prixTotal,
            'nbArticles' => $nbArticles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/compte', name: 'compte')]
    public function panierCompte(SecurityController $sc, EntityManagerInterface $em, Request $request): Response
    {
        $user = $sc->getUser();

        $adresses = $em->getRepository(Adresse::class)->findAll();
        $adresse = new Adresse();
        foreach ($adresses as $singleAdresse) {

            if ($singleAdresse->getUser()->getId() == $user->getId()) {
                $adresse = $singleAdresse;
            }
        }

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

        return $this->render('panier/compte.html.twig', [
            'user' => $user,
            'adresse' => $adresse,
            'panier' => $panier,
            'prixTotal' => $prixTotal,
            'nbArticles' => $nbArticles,
        ]);
    }

    /* #[IsGranted('ROLE_USER')]
     #[Route('/synthese', name: 'synthese')]
     public function panierSynthese(EntityManagerInterface $em, Request $request): Response
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

         return $this->render('panier/compte.html.twig', [
             'user' => $user,
             'adresse' => $adresse,
             'panier' => $panier,
             'prixTotal' => $prixTotal,
             'nbArticles' => $nbArticles,
         ]);
     }*/


}
