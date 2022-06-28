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
    #[Route(path: '/livres', name: 'listelivres', methods: ['GET'], defaults: ['title' => 'ArchHive'])]
    public function livres(EntityManagerInterface $em, LivreRepository $livreRepo, ManagerRegistry $doctrine)
    {

        return $this->render('listelivres.html.twig', ['livre' => $livre]);
    }
}