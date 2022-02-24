<?php

namespace App\Controller;

use App\Entity\Souscategorie;
use App\Form\SouscategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $souscategorie = new Souscategorie();
        $form = $this->createForm(SouscategorieType::class, $souscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($souscategorie);
            $entityManager->flush();

            return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souscategorie/new.html.twig', [
            'souscategorie' => $souscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idSousCategorie}', name: 'souscategorie_show', methods: ['GET'])]
    public function show(Souscategorie $souscategorie): Response
    {
        return $this->render('souscategorie/show.html.twig', [
            'souscategorie' => $souscategorie,
        ]);
    }

    #[Route('/{idSousCategorie}/edit', name: 'souscategorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Souscategorie $souscategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SouscategorieType::class, $souscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('souscategorie/edit.html.twig', [
            'souscategorie' => $souscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idSousCategorie}', name: 'souscategorie_delete', methods: ['POST'])]
    public function delete(Request $request, Souscategorie $souscategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $souscategorie->getIdSousCategorie(), $request->request->get('_token'))) {
            $entityManager->remove($souscategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('souscategorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
