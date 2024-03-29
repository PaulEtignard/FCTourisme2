<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\EditUserType;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecrityController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/inscription', name: 'app_inscription', methods: ['GET','POST'],priority: 1)]
    public function inscription(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $formUser = $this->createForm(InscriptionType::class, $user);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            $user->setCreatedAt(new \DateTime())
                ->setActif(0)
                ->setRoles(["ROLE_USER"]);
            $passwordHash = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($passwordHash);

            $userRepository->save($user,true);
            return $this->redirectToRoute('app_login');
        }
        return $this->renderForm('inscription/inscription.html.twig',[
            'formUser' => $formUser
        ]);
    }

    #[Route('/compte', name: 'app_compte')]
    public function compte(): Response
    {
        $user = $this->getUser();

        return $this->render('compte/compte.html.twig', [

        ]);

    }

    #[Route('/compte/modifier', name: 'app_compte_modifier',methods: ['GET','POST'],priority: 1)]
    public function editCompte(Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $formUser = $this->createForm(EditUserType::class, $user);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            $user->setupdatedAt( new \DateTime());
            $userRepository->save($user,true);
            return $this->redirectToRoute('app_compte');
        }
        return $this->renderForm('compte/modifierCompte.html.twig',[
            'formUser' => $formUser
        ]);


    }




}
