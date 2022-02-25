<?php

namespace App\Controller;

use App\Form\HeaderSearchType;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;

class BoutiqueController extends AbstractController
{

    #[Route('/boutique/{pageCourante}', name: 'boutique', requirements: ['pageCourante' => '\d+'])]
    public function index(ProduitRepository $pr, $pageCourante, Request $request): Response
    {


        $session = $request->getSession();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $formSearchBar = $this->createForm(HeaderSearchType::class);
        $formSearchBar->handleRequest($request);

        $nbResultats = 6;

        $filters = [];
        if ($session->has('filtres')) {
            $filters = $session->get('filtres');
        }

        $nbProduits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[0];
        $nbPage = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[1];
        $produits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[2];

        if ($pageCourante < 1) {
            $pageCourante = 1;
        }

        if ($pageCourante > $nbPage) {
            $pageCourante = $nbPage;
        }


        /** récupération des tris asc et desc **/
        if ($session->has('filtres') && !is_null($session->get('filtres')['ordre'])) {

            switch ($session->get('filtres')['ordre']) {
                case 1:
                    $produits = $pr->search($filters, $pageCourante, $nbResultats, 'ASC')[1];
                    break;
                case 2:
                    $produits = $pr->search($filters, $pageCourante, $nbResultats, 'DESC')[1];
                    break;
                /*case 3:
                    $produits = $pr->populariteDecroissante($filters, $pageCourante, $nbResultats);
                    break;*/
            }
        } // end if

        /************************ Si on soumet le formulaire de filtre ************************/
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();

            $nbProduits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[0];
            $nbPage = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[1];
            $produits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[2];

            /** mise en place des tris asc et desc après validation du form **/
            if (!is_null($form->get('ordre'))) {

                switch ($form->get('ordre')->getData()) {
                    case 1:
                        $produits = $pr->search($filters, $pageCourante, $nbResultats, 'ASC')[1];
                        break;
                    case 2:
                        $produits = $pr->search($filters, $pageCourante, $nbResultats, 'DESC')[1];
                        break;
                    /*case 3:
                        $produits = $pr->populariteDecroissante($filters, $pageCourante, $nbResultats);
                        break;*/
                }
            } // end if

            $session->set('filtres', $filters);
            $filters = $session->get('filtres');

        }
        /*********************** fin if soumission du formulaire de filtre ***********************/


        /************************ Si on soumet le formulaire de recherche de la navbar ************************/
        if ($formSearchBar->isSubmitted() && $formSearchBar->isValid()) {

            $filters = ['ordre' => null, 'searchBar' => $formSearchBar->get('rechercheNav')->getData(), 'marque' => null, 'prixMin' => null, 'prixMax' => null];

            $nbProduits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[0];
            $nbPage = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[1];
            $produits = $this->initSearch($pr, $filters, $pageCourante, $nbResultats)[2];
        }
        /*********************** fin if soumission de recherche de la navbar ***********************/

        /**Mise en session de la barre de recherche de la nav**/
        foreach ($request->request->all() as $values) {

            foreach ($values as $clef => $valeur) {
                if ($clef == 'rechercheNav') {
                    $session->set($clef, $valeur);
                }
            }
        }


        return $this->render('boutique/index.html.twig', [
            'produits' => $produits,
            'nbPage' => $nbPage,
            'pageCourante' => $pageCourante,
            'nbResultats' => $nbResultats,
            'nbProduits' => $nbProduits,
            'form' => $form->createView(),
            'filtres' => $filters,


        ]);
    }

    private function initSearch($pr, $filters, $pageCourante, $nbResultats): array
    {
        $nbProduits = count($pr->search($filters, $pageCourante, $nbResultats, '')[0]);

        $nbPage = floor($nbProduits / $nbResultats);

        if ($nbProduits % $nbResultats != 0) {
            $nbPage = floor($nbProduits / $nbResultats + 1);
        }

        if ($nbProduits == 0) {
            $nbPage = 1;
        }

        $produits = $pr->search($filters, $pageCourante, $nbResultats, '')[1];

        $results = [];
        array_push($results, $nbProduits, $nbPage, $produits);
        return $results;
    }

}
