<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route(path: '/profile')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: 'profile', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function index(User $user, int $id=1)
    {
      $currentUser = $this->getUser();
      // if($currentUser === $user)
      // {
      //   return $this->redirectToRoute('currentUser');
      // }
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

  #[Route('/userprofile', name: 'current_user')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]    //Permet de redemander une nouvelle fois le mot de passe de l'utilsateur pour qu'il puisse le modifier
  public function userProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
  {
    $user = $this->getUser();
    $userForm = $this->createForm(UserType::class, $user);
    $userForm->remove('mot_de_passe');
    $userForm->add('newPassword', PasswordType::class, ['label' => 'Nouveau mot de passe', 'required' => false]);
    $userForm->handleRequest($request);

    if ($userForm->isSubmitted() && $userForm->isValid()) 
    {
      $newPassword = $user->getNewPassword();

      if ($newPassword) 
      {
        $hash = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hash);
      }

      $em->flush();
      $this->addFlash('success', 'Modifications sauvegardées !');
    }

    return $this->render('user/index.html.twig', [
      'PWform' => $userForm->createView()
    ]);
  }

  #[Route('/settings', name: 'parametres')]
  #[IsGranted('ROLE_USER')]
  public function parametres()
  {
    return $this->render('user/parametres.html.twig', [
      'controller_name' => 'UserController',
    ]);
  }
}
