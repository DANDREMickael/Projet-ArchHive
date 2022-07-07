<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
  public function parametres(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository) : Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
    $user = $this->getUser();

    //Changement du profil
    $form = $this->createFormBuilder()
      ->add('image', FileType::class, 
      [
        'label' => 'Choisissez votre image de profil : ',
        'constraints' => 
        [
          new Image([
            'maxWidth' => 1920,
            'maxHeight' => 1080,
            'mimeTypes' => 
            [
              'image/png',
              'image/jpeg',
            ],
          'mimeTypesMessage' => 'Seules les images au format PNG et JPEG sont acceptées',
          ])  //Ajouter 'multiple' => true dans l'array pour que le navigateur permette d'envoyer plusieurs fichiers
        ]
      ])

      ->add('nom', TextType::class, 
      [
          'label' => 'Votre NOM :',
          'attr' => ['placeholder' => $user->getNom(), 'maxlength' => 32],
      ])

      ->add('prenom', TextType::class, 
      [
          'label' => 'Votre Prénom :',
          'attr' => ['placeholder' => $user->getPrenom(), 'maxlength' => 32],
      ])

      ->add('dateNaissance', BirthdayType::class, 
      [
          'label' => 'Votre date de naissance :',
      ])

      ->add('adresse', TextType::class,
            [
                'trim' => true,
                'label' => 'Votre Adresse :',
                'attr' => ['placeholder' => $user->getAdresse(), 'maxlength' => 255],
            ])

      ->add('codePostal', TextType::class,
      [
          'trim' => false,
          'label' => 'Votre CP :',
          'attr' => ['placeholder' => $user->getCodePostal(), 'maxlength' => 5],
      ])

      ->add('ville', TextType::class,
      [
          'trim' => true,
          'label' => 'Votre ville :',
          'attr' => ['placeholder' => $user->getVille(), 'maxlength' => 255],
      ])

      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFile $attachment */
      $attachment = $form->get('image')->getData();
      $originalFilename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME); //Récupère le nom original du fichier sans son extension
      $randomFileName = bin2hex(random_bytes(16));
      $extension = $attachment->guessExtension(); //Détermine le type de fichier par rapport à son contenu
      $filename= $originalFilename . '-' . $randomFileName . '.' . $extension;
      $attachment->move($this->getParameter('img_directory'), $filename);

      $user ->setNom($form->get('nom')->getData())
            ->setPrenom($form->get('prenom')->getData())
            ->setDateNaissance($form->get('dateNaissance')->getData())
            ->setAdresse($form->get('adresse')->getData())
            ->setCodePostal($form->get('codePostal')->getData())
            ->setVille($form->get('ville')->getData())
            ->setImage($filename);
      $entityManager->flush($user);

      $this->addFlash('success', 'Vos modifications ont bien été prises en compte');
      $this->redirectToRoute('profile');
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
