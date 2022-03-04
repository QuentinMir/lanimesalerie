<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Form\AvisType;
use App\Form\HeaderSearchType;
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

        return $this->render('default/index.html.twig', [
            'produits' => $produits,
            'categories' => $categories
        ]);
    }

    #[Route('/produit/{id}', name: 'singleProduct', requirements: ['id' => '\d+'])]
    public function getOneProduct(Produit $produit, EntityManagerInterface $em, Request $request, SecurityController $sc): Response
    {
        // récupérer les images
        $images = $produit->getImages();

        // récupérer les produits
        $produitAvis = $produit->getAvis();

        // récupérer la marque
        $marques = $em->getRepository(Marque::class)->findAll();
        foreach ($marques as $marque) {

            if ($produit->getIdMarque() == $marque) {
                $currentMarque = $marque;
            }
        }

        $note = 0;
        /*dd($produitAvis);*/

        /* foreach ($produitAvis)*/

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        $user = $sc->getUser();

        if ($form->isSubmitted() && $form->isValid()
        ) {

            $avis->setDate(new \DateTime());
            $avis->setUser($user);
            $avis->setProduit($produit);


            $em->persist($avis);
            $em->flush();


            return $this->redirect($request->headers->get('referer'));

        }


        return $this->render('default/produit.html.twig', [
            'produit' => $produit,
            'images' => $images,
            'form' => $form->createView(),
            'produitAvis' => $produitAvis,
            'marque' => $currentMarque,
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
