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
        for ($i = 1; $i <= 10; $i++)
        {
            $livre = New Livre;
            $livre->setTitre("Le titre du livre n°$i");
            $livre->setDescriptionLivre("C'est un super livre ! Dommage que je ne sache pas lire :c")
                  ->setDateParution(new \DateTime)
                  ->setImage("http://placehold.it/350x150");
            $em -> persist($livre);
        }
        $em -> flush($livre);

        //$livreRepo ->findBy([Livre::class], ['titre' => 'DESC']);

        return $this->render('listelivres.html.twig', ['livre' => $livre]);
    }
}