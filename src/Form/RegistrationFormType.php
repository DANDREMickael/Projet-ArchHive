<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use App\Entity\User;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, 
            [
                'label' => 'Votre NOM :',
                'attr' => ['placeholder' => 'Exemple : Capasse'],
                'constraints' => 
                [
                    //Contrainte de champs non vide
                    New NotBlank(message: 'Vous n\'avez pas entré votre nom'),
                ]
            ])

            ->add('prenom', TextType::class, 
            [
                'label' => 'Votre Prénom :',
                'attr' => ['placeholder' => 'Exemple : Michel'],
                'constraints' => 
                [
                    New NotBlank(message: 'Vous n\'avez pas entré votre prénom'),
                ]
            ])

            ->add('dateNaissance', BirthdayType::class, 
            [
                'label' => 'Votre date de naissance :',
            ])

            ->add('email', EmailType::class,
            [
                'trim' => true,        //Supprime les espaces
                'label' => 'Votre Email :',
                'attr' => ['placeholder' => 'Exemple : michelcapasse@gmail.com'],
                'constraints' => 
                [
                    New NotBlank
                    (
                        message: 'Adresse email nécessaire pour s\'inscrire'
                    ),

                    //Contrainte de validité de l'email
                    New Email
                    (
                        message: '{{ value }} n\’est pas une adresse email valide.', mode: 'strict'
                    )
                ]
            ])

            ->add('mot_de_passe', PasswordType::class, 
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Votre mot de passe :',
                'constraints' => 
                [
                    // Contrainte de longueur du mot de passe
                    New Length
                    (
                        min: 10,
                        max: 30,
                        minMessage: 'Minimum {{ limit }} caractères.',
                        maxMessage: 'Maximum {{ limit }} caractères.',
                    ),

                    new NotBlank
                    ([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                ]
            ])

            ->add('adresse', TextType::class,
            [
                'trim' => true,
                'label' => 'Votre Adresse :',
                'attr' => ['placeholder' => 'N° de voie et Rue'],
            ])

            ->add('codePostal', TextType::class,
            [
                'trim' => false,
                'label' => 'Votre CP :',
                'attr' => ['placeholder' => 'Exemple : 62200', 'maxlength' => 5],
            ])

            ->add('ville', CountryType::class,
            [
                'trim' => true,
                'label' => 'Votre Ville :',
                'attr' => ['placeholder' => 'Exemple : Boulogne'],
            ])

            ->add('cgu', CheckBoxType::class, 
            [
                'label' => 'J\'ai lu et j\'accepte les conditions générales et je suis d\'accord avec la Politique de Confidentialité concernant le traitement des données.',
                'constraints' =>
                [
                    new IsTrue
                    ([
                        //'mapped' => false,
                        'message' => 'Vous devez accepter les Conditions d\'utilisation.',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
