<?php

namespace App\Controller;

use App\Entity\Subsouscategorie;
use App\Form\SubsouscategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subsouscategorie')]
class SubsouscategorieController extends AbstractController
{
    #[Route('/', name: 'subsouscategorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $subsouscategories = $entityManager
            ->getRepository(Subsouscategorie::class)
            ->findAll();

        return $this->render('subsouscategorie/index.html.twig', [
            'subsouscategories' => $subsouscategories,
        ]);
    }

    #[Route('/new', name: 'subsouscategorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subsouscategorie = new Subsouscategorie();
        $form = $this->createForm(SubsouscategorieType::class, $subsouscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subsouscategorie);
            $entityManager->flush();

            return $this->redirectToRoute('subsouscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subsouscategorie/new.html.twig', [
            'subsouscategorie' => $subsouscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubSousCategorie}', name: 'subsouscategorie_show', methods: ['GET'])]
    public function show(Subsouscategorie $subsouscategorie): Response
    {
        return $this->render('subsouscategorie/show.html.twig', [
            'subsouscategorie' => $subsouscategorie,
        ]);
    }

    #[Route('/{idSubSousCategorie}/edit', name: 'subsouscategorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subsouscategorie $subsouscategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubsouscategorieType::class, $subsouscategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('subsouscategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subsouscategorie/edit.html.twig', [
            'subsouscategorie' => $subsouscategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{idSubSousCategorie}', name: 'subsouscategorie_delete', methods: ['POST'])]
    public function delete(Request $request, Subsouscategorie $subsouscategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subsouscategorie->getIdSubSousCategorie(), $request->request->get('_token'))) {
            $entityManager->remove($subsouscategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subsouscategorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
