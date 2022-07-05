<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;
use App\Entity\Livre;

class ListeLivresController extends AbstractController
{ 
    
    //Page des livres disponibles à l'empreint
    #[Route(path: '/livres', name: 'listelivres', methods: ['GET'])]
    public function livres(EntityManagerInterface $em, LivreRepository $livreRepo, ManagerRegistry $doctrine, Request $request) : Response
    {
        return $this->render('listelivres.html.twig', [
            'livres' => $livreRepo->findAll(),
        ]);
    }
}