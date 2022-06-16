<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use ProxyManager\ProxyGenerator\ValueHolder\MethodGenerator\Constructor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use DateTime;


class SecurityController extends AbstractController
{
    public function __construct($authenticator)
    {
        
    }

    #[Route(path: '/register', name:'register', methods: ['GET', 'PUT', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, UserRepository $user, $register_form, $datecrea, $data, $id_list)
    {
        $register_form -> handleRequest($request); //Vérifie si le formulaire est envoyé ou affiché

        if($register_form->isSubmitted() && $register_form->isValid())
        {   
            //Vérifie si l'id de l'utilisateur n'est pas déjà enregistré
            //$id_list=getData();     //Récupère tous les id utilisateur de la DB
            if(!$id_list)
            {
                dd($register_form->getData());
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $register_form->get('mot_de_passe')->getData()
                        )
                    );
                $datecrea->setCreatedAt(new DateTime());            //Définie la date de création du compte dde l'utilisateur
                $entityManager->persist($user);
                $entityManager->flush();

                //Message flash de confirmation d'inscription
                $this->addFlash('success', 'Vous êtes bien inscrit !');

                //Création du cookie de session de l'utilisateur
                $cookie = Cookie::create('session')
                ->withValue('est_connecte')
                ->withExpires(strtotime('Fri, 8-July-2022 17:00:00 GMT'))
                ->withHttpOnly(true)
                ->withSecure(true);

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }
        } 
        else
        {
            if($register_form->isSubmitted() && !$register_form->isValid())
            {
                //Message flash d'erreur
                $this->addFlash('error', 'Informations incorrectes. Veuillez réessayer.');
            }
        } 
    }

    #[Route(path: '/login', name: 'login', methods: ['GET', 'PUT', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser())       //Redirige automatiquement l'utilisateur vers une page différente si jamais il est déjà identifié et qu'il tente d'aller sur la page contenant le formulaire de connexion.
        {
            return $this->redirectToRoute('profile');
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier username entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
