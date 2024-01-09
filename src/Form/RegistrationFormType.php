<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo', TextType::class, [
            'label' => 'Pseudo*',
            'attr' => ['class' => 'form-control']
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse email*',
            'attr' => ['class' => 'form-control']
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => 'J\'accepte les conditions*',
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devriez accepter ces conditions.',
                ]),
            ],
            'attr' => ['class' => 'form-check-input']
        ])
        // ->add('plainPassword', PasswordType::class, [
        //     'mapped' => false,
        //     'label' => 'Mot de passe*',
        //     'attr' => [
        //         'class' => 'form-control',
        //         'autocomplete' => 'new-password'
        //     ],
        //     'constraints' => [
        //         new NotBlank([
        //             'message' => 'Veuillez entrer un mot de passe',
        //         ]),
        //         new Length([
        //             'min' => 6,
        //             'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractères minimum',
        //             'max' => 4096,
        //         ]),
        //     ],
        // ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe',
                'attr' => ['class' => 'form-control']
            ],
            'second_options' => ['label' => 'Confirmez le mot de passe',
            'attr' => ['class' => 'form-control']        
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractères minimum',
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('valider', SubmitType::class, [
            'label' => 'S\'inscrire',
            'attr' => ['class' => 'btn btn-success']
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
