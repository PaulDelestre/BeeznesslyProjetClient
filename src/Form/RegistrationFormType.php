<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Image;
use App\Form\ImageType;
use App\Entity\Provider;
use App\Entity\Expertise;
use App\Entity\TypeOfUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::Class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Email'
                    ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => [
                    'attr' => ['placeholder' => 'Mot de passe'],
                    'label' => false,
                ],
                'second_options'  => [
                    'attr' => ['placeholder' => 'Confirmation mot de passe'],
                    'label' => false,
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Prénom'
                    ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Nom'
                    ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte les CDG",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('adress', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse'
                    ]
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Code Postal'
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5
                    ]),
                ],
            ])
            ->add('town', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                    ]
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('provider', EntityType::class, [
                'class' => Provider::class,
                'choice_label' => 'type',
                'expanded' => false,
                'multiple' => false,
                'by_reference' => false,
                'label' => "Je suis un/une ",
            ])
            ->add('companyName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de mon entreprise'
                    ]
            ])
            ->add('siretNumber', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de SIRET'
                    ]
            ])
            ->add('expertise', EntityType::class, [
                'class' => Expertise::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
                'by_reference' => true,
                'label' => "Domaine(s) d'expertise(s) :",
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => true,
                'required' => true,
                'label' => 'images à ajouter',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
