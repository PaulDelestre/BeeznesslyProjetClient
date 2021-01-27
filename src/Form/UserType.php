<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'required' => true,
            'attr' => [
                'placeholder' => 'Prénom'
                ]
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => [
                'placeholder' => 'Nom'
                ]
        ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Email'
                    ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])
            ->add('companyName', TextType::class, [
                'label' => 'Nom de mon entreprise',
                'attr' => [
                    'placeholder' => 'Nom de mon entreprise'
                    ]
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'Numéro de Siret',
                'attr' => [
                    'placeholder' => 'Numéro de SIRET'
                    ]
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                    ]
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => 'Code postal',
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
            ->add('adress', TextareaType::class, [
                'label' => 'Adresse',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Adresse'
                    ]
            ])
            ->add('provider')
            ->add('profilePictureFile', VichFileType::class, [
                'required'      => false,
                'allow_delete'  => true,
                'download_uri' => true,
                'label' => "Ajouter une photo de profil",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
