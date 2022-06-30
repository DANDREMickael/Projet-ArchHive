<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpruntController extends AbstractController
{
    #[Route('/emprunts', name: 'emprunts')]
    #[IsGranted('ROLE_USER')]
    public function emprunts(): Response
    {
        return $this->render('emprunts/emprunts.html.twig', [
            'controller_name' => 'EmpruntController',
        ]);
    }
}
