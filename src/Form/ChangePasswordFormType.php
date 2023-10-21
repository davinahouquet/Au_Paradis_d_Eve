<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'
                ],
            ],
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir 6 caractères au minimum',
                        //Longueur maximum autorisée par Symfony pour des raisons de sécurité
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Nouveau mot de passe',
            ],
            'second_options' => [
                'label' => 'Confirmer le nouveau mot de passe',
            ],
            'invalid_message' => 'Les mots de passes doivent être similaires',
            'mapped' => false,
        ])
        ->add('valider', SubmitType::class, [
            'attr' => [
            'class' => 'btn btn-success'
            ]
        ]); 
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
