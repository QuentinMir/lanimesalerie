<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Entity\Souscategorie;
use App\Form\CarouselType;
use App\Repository\CarouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/carousel')]
class CarouselController extends AbstractController
{
    #[Route('/', name: 'carousel_index', methods: ['GET'])]
    public function index(CarouselRepository $carouselRepository): Response
    {
        return $this->render('carousel/index.html.twig', [
            'carousels' => $carouselRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'carousel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $carousel = new Carousel();
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadImage($form, $slugger, $carousel);

            $entityManager->persist($carousel);
            $entityManager->flush();

            return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/new.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'carousel_show', methods: ['GET'])]
    public function show(Carousel $carousel): Response
    {
        return $this->render('carousel/show.html.twig', [
            'carousel' => $carousel,
        ]);
    }

    #[Route('/{id}/edit', name: 'carousel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carousel $carousel, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadImage($form, $slugger, $carousel);
            $entityManager->flush();

            return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/edit.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'carousel_delete', methods: ['POST'])]
    public function delete(Request $request, Carousel $carousel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $carousel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($carousel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param SluggerInterface $slugger
     * @param Carousel $carousel
     * @return void
     */
    public function uploadImage(\Symfony\Component\Form\FormInterface $form, SluggerInterface $slugger, Carousel $carousel): void
    {
        $image = $form->get('image')->getData();
        if ($image) { // Génération d'un nouveau nom sécurisé et unique
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();


            $image->move(
                $this->getParameter('images_carousel'),
                $newFilename
            );

            $carousel->setImage($newFilename);
        }
    }

}
