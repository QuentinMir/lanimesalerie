<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Form\AdresseType;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
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
        return $this->formHandling($adresse, $request, $userPasswordHasher, $entityManager, $userAuthenticator, $authenticator);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/{adresse}/edit', name: 'user_edit')]
    public function editUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, Adresse $adresse): Response
    {


        return $this->formHandling($adresse, $request, $userPasswordHasher, $entityManager, $userAuthenticator, $authenticator);
    }

    /**
     * @param Adresse $adresse
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param AppAuthenticator $authenticator
     * @return Response|null
     */
    public function formHandling(Adresse $adresse, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator): ?Response
    {
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

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            $session = new Session();
            $session->invalidate();

            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logout');


    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/admin/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUserFromAdmin(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');


    }

}
