<?php

namespace App\Controller;

use App\Form\HeaderSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavSearchController extends AbstractController
{

    public function formNavBar(Request $request): Response
    {
        $form = $this->createForm(HeaderSearchType::class);
        $form->handleRequest($request);

        return $this->render('nav_search/index.html.twig', [
            'formSearchBar' => $form->createView()
        ]);
    }
}
