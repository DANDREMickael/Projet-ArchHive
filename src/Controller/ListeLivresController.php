<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeLivresController extends AbstractController
{ 
    
    //Page des livres disponibles à l'empreint
    #[Route(path: '/livres', name: 'listelivres', methods: ['GET'], defaults: ['title' => 'ArchHive'])]
    public function livres(string $name = 'element')  : Response
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        /*$livre = [
            'titre' => $dbtitre,
            'description' => $dbdescription_livre,
            'date_de_parution' => strtotime($dbparution),
            'auteur' => $dbauteur,
            'genre' => $dbgenre,
        ];*/
        //$livres = $livreRepository->findBy([], ['titre' => 'DESC']);

        return $this->render('listelivres.html.twig', /*['livre' => $livre]*/);
    }
}