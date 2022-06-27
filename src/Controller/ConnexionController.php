<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\LoginFormAuthenticator;
use App\Services\LoginVerifier;
use Doctrine\DBAL\Query;
use App\Entity\User;
use DateTime;

class ConnexionController extends AbstractController
{
    public function index(RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
    }

    function __construct()
    {
    }

    //Page de connexion
    #[Route(path: '/login', name:'connexion', methods: ['GET', 'PUT', 'POST'])]
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
            //Redirige automatiquement l'utilisateur vers une page différente si jamais il est déjà identifié et qu'il tente d'aller sur la page contenant le formulaire de connexion.
            if ($this->getUser())
            {
                return $this->redirectToRoute('profile');
            } 
            else 
            {
                $error = $authenticationUtils->getLastAuthenticationError();
                $lastUsername = $authenticationUtils->getLastUsername();
    
                return $this->render('security/connexion.html.twig', [
                    'controller_name'=> 'ConnexionController',
                    'last_username' => $lastUsername,
                    'error'         => $error,
                ]);

                // $mdp=getPassword($user);
                // $userPasswordHasher->hashPassword($user, $mdp->get('mot_de_passe')->getData());
                // if($mdp === $userPasswordHasher)
                // {
                //     return $userAuthenticator->authenticateUser(
                //         $user,
                //         $authenticator,
                //         $request
                //     );
                // }                     
            }
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

?>