<?php

namespace App\Controller;
use App\Services\LoginVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    //Page d'accueil
    #[Route(path: '/', name: 'accueil', methods: ['GET'])]
    #[Route(path: '/home', name: 'accueil1', methods: ['GET'])]
    #[Route(path: '/homepage', name: 'accueil2', methods: ['GET'])]
    #[Route(path: '/accueil', name: 'accueil3', methods: ['GET'], priority: 1)]
    public function index() : Response
    {
        return $this->render('accueil.html.twig');
    }

}

?>
