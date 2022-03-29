<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use App\Form\HeaderSearchType;
use App\Form\SearchType;
use App\Repository\ProduitPanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;

class BoutiqueController extends AbstractController
{

    #[Route('/boutique/{pageCourante}', name: 'boutique', requirements: ['pageCourante' => '\d+'])]
    public function index(ProduitRepository $pr, $pageCourante, Request $request, EntityManagerInterface $em): Response
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

        $categories = $em->getRepository(Categorie::class)->findAll();
        $allSouscategories = $em->getRepository(Souscategorie::class)->findAll();


        return $this->render('boutique/index.html.twig', [
            'produits' => $produits,
            'nbPage' => $nbPage,
            'pageCourante' => $pageCourante,
            'nbResultats' => $nbResultats,
            'nbProduits' => $nbProduits,
            'form' => $form->createView(),
            'filtres' => $filters,
            'categories' => $categories,
            'allSouscategories' => $allSouscategories,


        ]);
    }

    #[Route('/boutique/{categorie}/{pageCourante}', name: 'boutiqueCat', requirements: ['pageCourante' => '\d+', 'categorie' => '\d+'])]
    public function catDisplay(ProduitRepository $pr, $pageCourante, Request $request, Categorie $categorie, EntityManagerInterface $em): Response
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

        $nbProduits = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[0];
        $nbPage = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[1];
        $produits = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[2];

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
                    $produits = $pr->searchCat($filters, $pageCourante, $nbResultats, 'ASC', $categorie)[1];
                    break;
                case 2:
                    $produits = $pr->searchCat($filters, $pageCourante, $nbResultats, 'DESC', $categorie)[1];
                    break;
                /*case 3:
                    $produits = $pr->populariteDecroissante($filters, $pageCourante, $nbResultats);
                    break;*/
            }
        } // end if
        /************************ Si on soumet le formulaire de filtre ************************/
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();

            $nbProduits = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[0];
            $nbPage = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[1];
            $produits = $this->initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie)[2];

            /** mise en place des tris asc et desc après validation du form **/
            if (!is_null($form->get('ordre'))) {

                switch ($form->get('ordre')->getData()) {
                    case 1:
                        $produits = $pr->searchCat($filters, $pageCourante, $nbResultats, 'ASC', $categorie)[1];
                        break;
                    case 2:
                        $produits = $pr->searchCat($filters, $pageCourante, $nbResultats, 'DESC', $categorie)[1];
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

        $categories = $em->getRepository(Categorie::class)->findAll();
        $allSouscategories = $em->getRepository(Souscategorie::class)->findAll();


        return $this->render('boutique/index.html.twig', [
            'produits' => $produits,
            'nbPage' => $nbPage,
            'pageCourante' => $pageCourante,
            'nbResultats' => $nbResultats,
            'nbProduits' => $nbProduits,
            'form' => $form->createView(),
            'filtres' => $filters,
            'categorie' => $categorie,
            'categories' => $categories,
            'allSouscategories' => $allSouscategories,
        ]);
    }

    #[Route('/boutique/{categorie}/{souscategorie}/{pageCourante}', name: 'boutiqueSouscat', requirements: ['pageCourante' => '\d+', 'categorie' => '\d+', 'souscategorie' => '\d+'])]
    public function souscatDisplay(ProduitRepository $pr, $pageCourante, Request $request, Categorie $categorie, Souscategorie $souscategorie, EntityManagerInterface $em): Response
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

        $nbProduits = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[0];
        $nbPage = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[1];
        $produits = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[2];

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
                    $produits = $pr->searchSouscat($filters, $pageCourante, $nbResultats, 'ASC', $categorie, $souscategorie)[1];
                    break;
                case 2:
                    $produits = $pr->searchSouscat($filters, $pageCourante, $nbResultats, 'DESC', $categorie, $souscategorie)[1];
                    break;
                /*case 3:
                    $produits = $pr->populariteDecroissante($filters, $pageCourante, $nbResultats);
                    break;*/
            }
        } // end if
        /************************ Si on soumet le formulaire de filtre ************************/
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();

            $nbProduits = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[0];
            $nbPage = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[1];
            $produits = $this->initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie)[2];

            /** mise en place des tris asc et desc après validation du form **/
            if (!is_null($form->get('ordre'))) {

                switch ($form->get('ordre')->getData()) {
                    case 1:
                        $produits = $pr->searchSouscat($filters, $pageCourante, $nbResultats, 'ASC', $categorie, $souscategorie)[1];
                        break;
                    case 2:
                        $produits = $pr->searchSouscat($filters, $pageCourante, $nbResultats, 'DESC', $categorie, $souscategorie)[1];
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

        $souscategories = [];
        $subsouscategories = [];

        $allSubsouscategories = $em->getRepository(Subsouscategorie::class)->findAll();
        $allSouscategories = $em->getRepository(Souscategorie::class)->findAll();

        /** Récupération des sous catégories puis des sub sous catégories **/
        foreach ($allSouscategories as $singleSouscategorie) {
            if ($singleSouscategorie->getCategorie()->getId() == $categorie->getId()) {
                $souscategories[] = $singleSouscategorie;

                /** Récupération des sub sous catégories **/
                foreach ($allSubsouscategories as $singleSubsouscategorie) {
                    if ($singleSubsouscategorie->getSouscategorie()->getId() == $singleSouscategorie->getId()) {
                        $subsouscategories[] = $singleSubsouscategorie;
                    }
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
            'categorie' => $categorie,
            'souscategorie' => $souscategorie,
            'souscategories' => $souscategories,
            'subsouscategories' => $subsouscategories,


        ]);
    }

    #[Route('/boutique/{categorie}/{souscategorie}/{subsouscategorie}/{pageCourante}', name: 'boutiqueSubsouscat', requirements: ['pageCourante' => '\d+', 'categorie' => '\d+', 'souscategorie' => '\d+', 'subsouscategorie' => '\d+'])]
    public function subsouscatDisplay(ProduitRepository $pr, $pageCourante, Request $request, Categorie $categorie, Souscategorie $souscategorie, Subsouscategorie $subsouscategorie, EntityManagerInterface $em): Response
    {


        $session = $request->getSession();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $formSearchBar = $this->createForm(HeaderSearchType::class);
        $formSearchBar->handleRequest($request);

        $nbResultats = 3;

        $filters = [];
        if ($session->has('filtres')) {
            $filters = $session->get('filtres');
        }

        $nbProduits = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[0];
        $nbPage = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[1];
        $produits = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[2];

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
                    $produits = $pr->searchSubSouscat($filters, $pageCourante, $nbResultats, 'ASC', $categorie, $souscategorie, $subsouscategorie)[1];
                    break;
                case 2:
                    $produits = $pr->searchSubSouscat($filters, $pageCourante, $nbResultats, 'DESC', $categorie, $souscategorie, $subsouscategorie)[1];
                    break;
                /*case 3:
                    $produits = $pr->populariteDecroissante($filters, $pageCourante, $nbResultats);
                    break;*/
            }
        } // end if
        /************************ Si on soumet le formulaire de filtre ************************/
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();

            $nbProduits = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[0];
            $nbPage = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[1];
            $produits = $this->initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie)[2];

            /** mise en place des tris asc et desc après validation du form **/
            if (!is_null($form->get('ordre'))) {

                switch ($form->get('ordre')->getData()) {
                    case 1:
                        $produits = $pr->searchSubSouscat($filters, $pageCourante, $nbResultats, 'ASC', $categorie, $souscategorie, $subsouscategorie)[1];
                        break;
                    case 2:
                        $produits = $pr->searchSubSouscat($filters, $pageCourante, $nbResultats, 'DESC', $categorie, $souscategorie, $subsouscategorie)[1];
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

        $souscategories = [];
        $subsouscategories = [];

        $allSubsouscategories = $em->getRepository(Subsouscategorie::class)->findAll();
        $allSouscategories = $em->getRepository(Souscategorie::class)->findAll();

        /** Récupération des sous catégories puis des sub sous catégories **/
        foreach ($allSouscategories as $singleSouscategorie) {
            if ($singleSouscategorie->getCategorie()->getId() == $categorie->getId()) {
                $souscategories[] = $singleSouscategorie;

                /** Récupération des sub sous catégories **/
                foreach ($allSubsouscategories as $singleSubsouscategorie) {
                    if ($singleSubsouscategorie->getSouscategorie()->getId() == $singleSouscategorie->getId()) {
                        $subsouscategories[] = $singleSubsouscategorie;
                    }
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
            'categorie' => $categorie,
            'souscategorie' => $souscategorie,
            'souscategories' => $souscategories,
            'subsouscategorie' => $subsouscategorie,
            'subsouscategories' => $subsouscategories,


        ]);
    }

    /****************************************TOUS LES PRODUITS*************************************************/

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


    /****************************************CATEGORIE*************************************************/

    private function initSearchCat($pr, $filters, $pageCourante, $nbResultats, $categorie): array
    {
        $nbProduits = count($pr->searchCat($filters, $pageCourante, $nbResultats, '', $categorie)[0]);

        $nbPage = floor($nbProduits / $nbResultats);

        if ($nbProduits % $nbResultats != 0) {
            $nbPage = floor($nbProduits / $nbResultats + 1);
        }

        if ($nbProduits == 0) {
            $nbPage = 1;
        }

        $produits = $pr->searchCat($filters, $pageCourante, $nbResultats, '', $categorie)[1];

        $results = [];
        array_push($results, $nbProduits, $nbPage, $produits);
        return $results;
    }


    /****************************************SOUS CATEGORIE*************************************************/

    private function initSearchSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie): array
    {
        $nbProduits = count($pr->searchSouscat($filters, $pageCourante, $nbResultats, '', $categorie, $souscategorie)[0]);

        $nbPage = floor($nbProduits / $nbResultats);

        if ($nbProduits % $nbResultats != 0) {
            $nbPage = floor($nbProduits / $nbResultats + 1);
        }

        if ($nbProduits == 0) {
            $nbPage = 1;
        }

        $produits = $pr->searchSouscat($filters, $pageCourante, $nbResultats, '', $categorie, $souscategorie)[1];

        $results = [];
        array_push($results, $nbProduits, $nbPage, $produits);
        return $results;
    }


    /****************************************SUB SOUS CATEGORIE*************************************************/

    private function initSearchSubSouscat($pr, $filters, $pageCourante, $nbResultats, $categorie, $souscategorie, $subsouscategorie): array
    {
        $nbProduits = count($pr->searchSubSouscat($filters, $pageCourante, $nbResultats, '', $categorie, $souscategorie, $subsouscategorie)[0]);

        $nbPage = floor($nbProduits / $nbResultats);

        if ($nbProduits % $nbResultats != 0) {
            $nbPage = floor($nbProduits / $nbResultats + 1);
        }

        if ($nbProduits == 0) {
            $nbPage = 1;
        }

        $produits = $pr->searchSubSouscat($filters, $pageCourante, $nbResultats, '', $categorie, $souscategorie, $subsouscategorie)[1];

        $results = [];
        array_push($results, $nbProduits, $nbPage, $produits);
        return $results;
    }


}
