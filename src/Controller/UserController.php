<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Entity\User;

#[Route(path: '/profile')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: 'profile', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function index(User $user, int $id=1)
    {
      $currentUser = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
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

  #[Route('/changemypass', name: 'reset_current_user_password')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]    //Permet de redemander une nouvelle fois le mot de passe de l'utilsateur pour qu'il puisse le modifier
    public function mdpprofile(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager) : Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('mot_de_passe')->getData()
            );

            $user->setPassword($encodedPassword);
            $entityManager->flush();

            return $this->redirectToRoute('profile');
        }

            return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

}
