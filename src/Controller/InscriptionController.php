<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\LoginFormAuthenticator;
use App\Form\RegistrationFormType;
use App\Entity\User;
use DateTime;

class InscriptionController extends AbstractController
{   
    //Page d'inscription : formulaire
    #[Route(path: '/register', name:'inscription', methods: ['GET', 'PUT', 'POST'])]
    public function inscription(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        //Vérifier si l'utilisateur est déjà connecté ou non 
        if ($this->getUser())
            {
                return $this->redirectToRoute('profile');
            } 
        else 
        {
            // encode the plain password
            $user = new User();
            $register_form = $this->createForm(RegistrationFormType::class, $user);
            $register_form -> handleRequest($request); //Vérifie si le formulaire est envoyé ou affiché

            if($register_form->isSubmitted() && $register_form->isValid()) 
            {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $register_form->get('mot_de_passe')->getData()
                    )
                );
                $user->setImage('userpp.svg');
                $entityManager->persist($user);
                $entityManager->flush();
                
                //Message flash de confirmation d'inscription
                $this->addFlash('success', 'Vous êtes bien inscrit ! Vérifiez vos emails.');

                //Envoi du mail de bienvenue
                $email = new TemplatedEmail();
                $email->to($user->getEmail())
                ->subject('Bienvenue sur ArchHive !')
                ->htmlTemplate('@email_templates/email_bienvenue.html.twig')
                ->context([
                'prenom_de_linscrits' => $user->getPrenom()
                ]);
                $mailer->send($email);

                //Création du cookie de session de l'utilisateur
                $cookie = Cookie::create('session')
                    ->withValue('est_connecte')
                    ->withExpires(strtotime('New DateTime'))
                    ->withHttpOnly(true)
                    ->withSecure(true);

                //Permet de connecter l'utilisateur directement après qu'il se soit inscrit
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }

            if ($register_form->isSubmitted() && !$register_form->isValid()) {
                //Message flash d'erreur
                $this->addFlash('danger', 'Informations incorrectes. Veuillez réessayer.');
            }

            return $this->render('security/inscription.html.twig', [
                'register_form_view' => $register_form->createView(),
            ]);
        }   
    }
}

?>