<?php

namespace App\Controller;

use App\Entity\Souscategorie;
use App\Form\SouscategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/souscategorie')]
class SouscategorieController extends AbstractController
{
    #[Route('/', name: 'souscategorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $souscategories = $entityManager
            ->getRepository(Souscategorie::class)
            ->findAll();

        return $this->render('souscategorie/index.html.twig', [
            'souscategories' => $souscategories,
        ]);
    }

    #[Route('/new', name: 'souscategorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $souscategorie = new Souscategorie();
        $form = $this->createForm(SouscategorieType::class, $souscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadImage($form, $slugger, $souscategorie);


            $entityManager->persist($souscategorie);
            $entityManager->flush();

            return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souscategorie/new.html.twig', [
            'souscategorie' => $souscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'souscategorie_show', methods: ['GET'])]
    public function show(Souscategorie $souscategorie): Response
    {
        return $this->render('souscategorie/show.html.twig', [
            'souscategorie' => $souscategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'souscategorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Souscategorie $souscategorie, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SouscategorieType::class, $souscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadImage($form, $slugger, $souscategorie);

            $entityManager->flush();

            return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souscategorie/edit.html.twig', [
            'souscategorie' => $souscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'souscategorie_delete', methods: ['POST'])]
    public function delete(Request $request, Souscategorie $souscategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $souscategorie->getid(), $request->request->get('_token'))) {
            $entityManager->remove($souscategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param SluggerInterface $slugger
     * @param Souscategorie $souscategorie
     * @return void
     */
    public function uploadImage(\Symfony\Component\Form\FormInterface $form, SluggerInterface $slugger, Souscategorie $souscategorie): void
    {
        $image = $form->get('imageLink')->getData();
        if ($image) { // Génération d'un nouveau nom sécurisé et unique
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();


            $image->move(
                $this->getParameter('images_souscategories'),
                $newFilename
            );

            // Dans ma BDD, j'ajoute le nom unique du fichier pour le retrouver
            $souscategorie->setImageLink($newFilename);
        }
    }
}
