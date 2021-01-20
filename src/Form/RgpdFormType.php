<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RgpdFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => "J'accepte que mes coordonnées soient partagées avec l'auteur du Ebook",
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les CGU.',
                ]),
            ],
        ])
        ;
    }
}
