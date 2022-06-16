<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormType;
use App\Entity\User;

class InscriptionController extends AbstractController
{   
    //Vérifier si l'utilisateur est déjà connecté ou non 
    public function index(RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
    }
    
    //Page d'inscription : formulaire
    #[Route(path: '/register', name:'inscription', methods: ['GET', 'PUT', 'POST'])]
    public function inscription()
    {
        //gère l'affichage du formulaire d'inscription
        $user = new User();
        $register_form = $this->createForm(RegistrationFormType::class, $user);

        return $this->render('security/inscription.html.twig', 
        [
            'register_form_view' => $register_form->createView()
        ]);
    }
}

?>