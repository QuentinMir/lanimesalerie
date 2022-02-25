<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Form\AdresseType;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        /*$user = new User();*/
        $adresse = new Adresse();
        /*$form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);*/
        $adresseForm = $this->createForm(AdresseType::class, $adresse);
        $adresseForm->handleRequest($request);


        if ($adresseForm->isSubmitted() && $adresseForm->isValid()) {
            $user = $adresseForm->getData()->getUser();

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $adresseForm->get('user')->get('plainPassword')->getData()
                )
            );
            $user->setDateInscription(new \DateTime());

            $adresse->setUser($user);

            $entityManager->persist($user);
            $entityManager->persist($adresse);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            /*'registrationForm' => $form->createView(),*/
            'adresseForm' => $adresseForm->createView(),
        ]);
    }
}
