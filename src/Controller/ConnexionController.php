<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use App\Services\LoginVerifier;
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
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        {

            if ($this->getUser()) 
            {
                return $this->redirectToRoute('profile');
            }
    
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('security/connexion.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        }
    }
}

?>