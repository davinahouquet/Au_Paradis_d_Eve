<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CoordonneesConnectedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email*',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'value' => 'stewie@griffin'
            ]
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Adresse*',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'value' => '31 Spooner Street'
            ]
        ])
        ->add('cp', TextType::class, [
            'label' => 'Code postal*',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'value' => '00093'
            ]
        ])
        ->add('ville', TextType::class, [
            'label' => 'Ville*',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'value' => 'Quahog'
                ]
        ])
        ->add('pays', TextType::class, [
                'label' => 'Pays*',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'value' => 'Rhode Island'
                ]
        ])
        // ->add('prixTotal', MoneyType::class, [
        //         'label' => 'Prix total',
        //         'disabled' => true, // lecture seule
        //         'currency' => 'EUR', // Devise
        // ])
        ->add('souvenir', CheckboxType::class, [
                'label' => 'Se souvenir de mon adresse pour de futures réservations',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ]
        ])
        ->add('valider', SubmitType::class, [
            'label' => 'Passer au paiement',
            'attr' => [
                'class' => 'btn btn-success',
            ]
        ]);
        ;
    }
}