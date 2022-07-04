<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\Image;
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
    #[Route('/', name: 'profile')]
    #[IsGranted('ROLE_USER')]
    public function index()
    {
      $currentUser = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

  #[Route('/settings', name: 'parametres', methods: ['GET', 'PUT', 'POST'])]
  #[IsGranted('ROLE_USER')]
  public function parametres(Request $request) : Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

    //Changement de la photo de profil
    $form = $this->createFormBuilder()
      ->add('image_profile', FileType::class, 
        [
          'label' => 'Choisissez votre image de profil : ',
          'constraints' => 
          [
            new Image([
              'maxWidth' => 100,
              'maxHeight' => 100,
              'mimeTypes' => 
              [
                'image/png',
                'image/jpeg',
              ],
            'mimeTypesMessage' => 'Seules les images au format PNG et JPEG sont acceptées',
            ])  //Ajouter 'multiple' => true dans l'array pour que le navigateur permette d'envoyer plusieurs fichiers
          ]
        ])

      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFile $attachment */
      $attachments = $form->get('image_profile')->getData();
      foreach ($attachments as $attachment) 
      {
        $originalFilename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME); //Récupère le nom original du fichier sans son extension
        $randomFileName = bin2hex(random_bytes(16));
        $extension = $attachment->guessExtension(); //Détermine le type de fichier par rapport à son contenu
        $attachment->move('image_profile', $originalFilename . '-' . $randomFileName . '.' . $extension);
      }
    }

    return $this->render('user/parametres.html.twig', [
      'settingsform' => $form->createView(),
    ]);
  }

  #[ Route('/changemypass', name: 'reset_current_user_password', methods: ['GET', 'PUT', 'POST'])]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function mdpprofile(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
  {
    //Permet de redemander une nouvelle fois le mot de passe de l'utilsateur pour qu'il puisse le modifier
    // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    $user = $this->getUser();

    $form = $this->createForm(ChangePasswordFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      // Encode et modifie le mot de passe
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
