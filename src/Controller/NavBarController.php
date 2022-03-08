<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavBarController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $sousCategories = $entityManager->getRepository(Souscategorie::class)->findAll();
        $subSousCategories = $entityManager->getRepository(Subsouscategorie::class)->findAll();
        return $this->render('parts/header.html.twig', [
            'categories' => $categories,
            'sousCategories' => $sousCategories,
            'subSousCategories' => $subSousCategories,
        ]);
    }
}
