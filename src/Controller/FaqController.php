<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/faq', name: 'faq_')]
class FaqController extends AbstractController
{
    #[Route('/livraison', name: 'livraison')]
    public function faqLivraison(): Response
    {
        return $this->render('faq/index.html.twig');
    }

    #[Route('/retour', name: 'retour')]
    public function faqRetour(): Response
    {
        return $this->render('faq/index.html.twig');
    }

    #[Route('/paiement', name: 'paiement')]
    public function faqPaiement(): Response
    {
        return $this->render('faq/index.html.twig');
    }

    #[Route('/commande', name: 'commande')]
    public function faqCommande(): Response
    {
        return $this->render('faq/index.html.twig');
    }
}
