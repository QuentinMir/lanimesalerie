<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Register2Controller extends AbstractController
{
    #[Route('/register2', name: 'register2')]
    public function index(): Response
    {
        return $this->render('register/register.html.twig');
    }
}
