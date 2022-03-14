<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'categorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadIcon($form, $slugger, $categorie);

            $this->uploadBanner($form, $slugger, $categorie);

            $this->uploadIndex($form, $slugger, $categorie);


            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadIcon($form, $slugger, $categorie);

            $this->uploadBanner($form, $slugger, $categorie);

            $this->uploadIndex($form, $slugger, $categorie);

            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getid(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param SluggerInterface $slugger
     * @param Categorie $categorie
     * @return void
     */
    public function uploadIcon(\Symfony\Component\Form\FormInterface $form, SluggerInterface $slugger, Categorie $categorie): void
    {
        $icon = $form->get('iconImage')->getData();
        if ($icon) { // Génération d'un nouveau nom sécurisé et unique
            $originalFilenameIcon = pathinfo($icon->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilenameIcon = $slugger->slug($originalFilenameIcon);
            $newFilenameIcon = $safeFilenameIcon . '-' . uniqid() . '.' . $icon->guessExtension();


            $icon->move(
                $this->getParameter('images_categories'),
                $newFilenameIcon
            );

            $categorie->setIconImage($newFilenameIcon);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param SluggerInterface $slugger
     * @param Categorie $categorie
     * @return void
     */
    public function uploadBanner(\Symfony\Component\Form\FormInterface $form, SluggerInterface $slugger, Categorie $categorie): void
    {
        $banner = $form->get('bannerImage')->getData();
        if ($banner) { // Génération d'un nouveau nom sécurisé et unique
            $originalFilenameBanner = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilenameBanner = $slugger->slug($originalFilenameBanner);
            $newFilenameBanner = $safeFilenameBanner . '-' . uniqid() . '.' . $banner->guessExtension();


            $banner->move(
                $this->getParameter('images_categories'),
                $newFilenameBanner
            );

            $categorie->setBannerImage($newFilenameBanner);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param SluggerInterface $slugger
     * @param Categorie $categorie
     * @return void
     */
    public function uploadIndex(\Symfony\Component\Form\FormInterface $form, SluggerInterface $slugger, Categorie $categorie): void
    {
        $index = $form->get('indexImage')->getData();
        if ($index) { // Génération d'un nouveau nom sécurisé et unique
            $originalFilenameIndex = pathinfo($index->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilenameIndex = $slugger->slug($originalFilenameIndex);
            $newFilenameIndex = $safeFilenameIndex . '-' . uniqid() . '.' . $index->guessExtension();


            $index->move(
                $this->getParameter('images_categories'),
                $newFilenameIndex
            );

            $categorie->setIndexImage($newFilenameIndex);
        }
    }
}
