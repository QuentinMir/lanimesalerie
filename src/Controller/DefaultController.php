<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Avis;
use App\Entity\Carousel;
use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\ProduitPanier;
use App\Entity\RandomNumber;
use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use App\Entity\Vote;
use App\Form\AvisType;
use App\Form\HeaderSearchType;
use App\Form\SearchType;
use App\Form\TriAvisType;
use App\Repository\AvisRepository;
use App\Repository\ProduitPanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function index(EntityManagerInterface $entityManager, ProduitPanierRepository $pr): Response
    {


        $allProduits = $this->produitRepository->findAll();
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $quantity = 1;
        $carouselItems = $entityManager->getRepository(Carousel::class)->findAll();

        /** récupération des 12 produits les plus vendus **/
        $produitsOrdonnes = $pr->getProduitsVentesDesc();
        $produits = [];

        foreach ($produitsOrdonnes as $produitOrdonne) {

            /** Comparaison des produits ordonnés aux produits et injection dans le tableau de l'objet Produit **/
            foreach ($allProduits as $produit) {
                if ($produitOrdonne['id'] == $produit->getId()) {
                    $produits[] = $produit;
                }
            }
        }

        return $this->render('default/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'quantity' => $quantity,
            'carouselItems' => $carouselItems,
        ]);
    }

    #[Route('/produit/{id}', name: 'singleProduct', requirements: ['id' => '\d+'])]
    public function getOneProduct(Produit $produit, EntityManagerInterface $em, Request $request, SecurityController $sc, AvisRepository $ar): Response
    {

        /** récupérer les images **/
        $images = $produit->getImages();

        /** récupérer la marque **/
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

        /** récupération des produits de même catégorie **/
        $categorie = $produit->getIdCategorie();
        $produits = $em->getRepository(Produit::class)->findBy(['idCategorie' => $categorie]);

        /** on enlève le produit actuel des produits de même catégorie **/
        if (in_array($produit, $produits)) {
            $key = array_search($produit, $produits);
            unset($produits[$key]);
        }
        /** on enlève le nombre de résultats a 14 **/
        $produits = array_slice($produits, 0, 14);


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
            'produits' => $produits,
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

    #[Route('/contact/confirmation', name: 'contact-confirmation')]
    public function displayContactConfirmationPage(): Response
    {

        return $this->render('contact/contact2.html.twig');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/compte', name: 'compte')]
    public function displayCompte(SecurityController $sc, EntityManagerInterface $em): Response
    {
        $user = $sc->getUser();

        $adresses = $em->getRepository(Adresse::class)->findAll();
        $adresse = new Adresse();

        foreach ($adresses as $singleAdresse) {

            if ($singleAdresse->getUser()->getId() == $user->getId()) {
                $adresse = $singleAdresse;
            }
        }

        return $this->render('default/compte.html.twig', [
            'user' => $user,
            'adresse' => $adresse
        ]);
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

    #[IsGranted('ROLE_USER')]
    #[Route('/commande', name: 'commande')]
    public function commandeIndex(EntityManagerInterface $em, SecurityController $sc): Response
    {
        $user = $sc->getUser();
        $commandes = $em->getRepository(Commande::class)->findBy(['user' => $user]);


        return $this->render('default/commande.html.twig', [
            'commandes' => $commandes,
        ]);
    }


    #[Route('/animalerie-3.0', name: '3.0')]
    public function spaceIndex(Request $request, SecurityController $sc, EntityManagerInterface $entityManager): Response
    {

        $user = $sc->getUser();
        /** on récupère le nombre aléatoire. S'il n'exite pas on le crée une fois et une seule **/
        $randomNumberEntity = $entityManager->getRepository(RandomNumber::class)->find(1);
        if (is_null($randomNumberEntity)) {
            $randomNumberEntity = new RandomNumber();
            $randomNumberEntity->setNumber(rand(0, 100000));
            $entityManager->persist($randomNumberEntity);
            $entityManager->flush();

        }

        $randomNumber = $randomNumberEntity->getNumber();

        $voted = false;
        $winner = false;
        $finished = false;
        $numero = $request->get('numero');
        $votes = $entityManager->getRepository(Vote::class)->findAll();

        $vote = new Vote();
        if (!is_null($user) && !is_null($numero)) {
            $vote->setUser($user);
            $vote->setNumero($numero);
            $entityManager->persist($vote);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('3.0'));
        }

        foreach ($votes as $singleVote) {
            if ($singleVote->getNumero() == $randomNumber) {
                /** Si quelqu'un trouve on arrête le jeu **/
                $finished = true;
            }
            if ($singleVote->getUser() == $user) {
                /** Si l'user a déjà voté il ne peut plus rejouer **/
                $voted = true;
                if ($singleVote->getNumero() == $randomNumber) {
                    $winner = $singleVote->getUser();
                }
            }
        }


        return $this->render('default/space.html.twig', [
            'voted' => $voted,
            'winner' => $winner,
            'finished' => $finished,
            'randomNumber' => $randomNumber,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/commande/{id}', name: 'facture', requirements: ['id' => '\d+'])]
    public function displayFacture(EntityManagerInterface $em, SecurityController $sc, Commande $commande): Response
    {
        /*$user = $sc->getUser();
        $commandes = $em->getRepository(Commande::class)->findBy(['user' => $user]);*/


        return $this->render('default/facture.html.twig', [
            'commande' => $commande,
        ]);
    }
}
