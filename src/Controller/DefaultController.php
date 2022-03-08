<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use App\Form\AvisType;
use App\Form\HeaderSearchType;
use App\Form\SearchType;
use App\Form\TriAvisType;
use App\Repository\AvisRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private ProduitRepository $produitRepository;


    public function __construct(ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
    }


    #[Route('/', name: 'default')]
    public function index(EntityManagerInterface $entityManager): Response
    {


        $produits = $this->produitRepository->findAll();
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $quantity = 1;

        return $this->render('default/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'quantity' => $quantity
        ]);
    }

    #[Route('/produit/{id}', name: 'singleProduct', requirements: ['id' => '\d+'])]
    public function getOneProduct(Produit $produit, EntityManagerInterface $em, Request $request, SecurityController $sc, AvisRepository $ar): Response
    {


        // récupérer les images
        $images = $produit->getImages();


        // récupérer la marque
        $marques = $em->getRepository(Marque::class)->findAll();
        foreach ($marques as $marque) {

            if ($produit->getIdMarque() == $marque) {
                $currentMarque = $marque;
            }
        }


        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        $user = $sc->getUser();

        /** traitement formulaire d'avis **/
        if ($form->isSubmitted() && $form->isValid()
        ) {

            $avis->setDate(new \DateTime());
            $avis->setUser($user);
            $avis->setProduit($produit);


            $em->persist($avis);
            $em->flush();


            return $this->redirect($request->headers->get('referer'));

        }

        $filters = [];

        $formTriAvis = $this->createForm(TriAvisType::class);
        $formTriAvis->handleRequest($request);


        /** récupérer les avis **/
        $produitAvis = $ar->search($filters, '', $produit, '');

        /** formaulaire de tri **/
        if ($formTriAvis->isSubmitted() && $formTriAvis->isValid()) {
            $filters = $formTriAvis->getData();


            if (!is_null($formTriAvis->get('ordre'))) {

                switch ($filters['ordre']) {
                    case 1:
                        $produitAvis = $ar->search($filters, 'ASC', $produit, 'note');
                        break;
                    case 2:
                        $produitAvis = $ar->search($filters, 'DESC', $produit, 'note');
                        break;
                    case 3:
                        $produitAvis = $ar->search($filters, 'DESC', $produit, 'date');
                        break;
                }
            }

        }

        /** calcul de la note moyenne et récupération du count de notes individuelles **/
        $note = 0;
        $star1 = 0;
        $star2 = 0;
        $star3 = 0;
        $star4 = 0;
        $star5 = 0;
        foreach ($produitAvis as $noteProduit) {
            /** moyenne des notes**/
            $note += $noteProduit->getNote();

            /** tri des notes et somme **/
            switch ($noteProduit->getNote()) {
                case 1:
                    $star1++;
                    break;
                case 2:
                    $star2++;
                    break;
                case 3:
                    $star3++;
                    break;
                case 4:
                    $star4++;
                    break;
                case 5:
                    $star5++;
                    break;
            }
        }
        if (count($produitAvis) > 0) {
            $note = $note / count($produitAvis);
        }

        $quantity = 1;
        if ($request->get('quantite') != null) {
            $quantity = intval($request->get('quantite'));
        }


        return $this->render('default/produit.html.twig', [
            'produit' => $produit,
            'images' => $images,
            'form' => $form->createView(),
            'produitAvis' => $produitAvis,
            'marque' => $currentMarque,
            'formTriAvis' => $formTriAvis->createView(),
            'filters' => $filters,
            'note' => $note,
            'star1' => $star1,
            'star2' => $star2,
            'star3' => $star3,
            'star4' => $star4,
            'star5' => $star5,
            'quantity' => $quantity,
        ]);
    }

    #[Route('/categorie/{categorie}', name: 'categorie')]
    public function displayCategorie(EntityManagerInterface $em, Categorie $categorie): Response
    {

        $souscategories = [];
        $subsouscategories = [];

        $allSubsouscategories = $em->getRepository(Subsouscategorie::class)->findAll();
        $allSouscategories = $em->getRepository(Souscategorie::class)->findAll();

        /** Récupération des sous catégories puis des sub sous catégories **/
        foreach ($allSouscategories as $souscategorie) {
            if ($souscategorie->getCategorie()->getId() == $categorie->getId()) {
                $souscategories[] = $souscategorie;

                /** Récupération des sub sous catégories **/
                foreach ($allSubsouscategories as $subsouscategorie) {
                    if ($subsouscategorie->getSouscategorie()->getId() == $souscategorie->getId()) {
                        $subsouscategories[] = $subsouscategorie;
                    }
                }

            }
        }



        /** Récupération des produits **/
        $produits = $categorie->getProduits();


        return $this->render('default/categorie.html.twig', [
            'categorie' => $categorie,
            'souscategories' => $souscategories,
            'subsouscategories' => $subsouscategories,
            'produits' => $produits,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function displayContactPage(): Response
    {

        return $this->render('contact/contact.html.twig');
    }

    #[Route('/mentions_legales', name: 'mentionsLG')]
    public function displayMentionsLG(): Response
    {

        return $this->render('default/mentionsLG.html.twig');
    }

    #[Route('/cgv', name: 'cgv')]
    public function displayCGV(): Response
    {

        return $this->render('default/cgv.html.twig');
    }

    #[Route('/protection-des-donnees', name: 'pdd')]
    public function displayPDD(): Response
    {

        return $this->render('default/pdd.html.twig');
    }

    #[Route('/conditions-de-resiliation', name: 'cdr')]
    public function displayCDR(): Response
    {

        return $this->render('default/conditionsResiliation.html.twig');
    }
}
