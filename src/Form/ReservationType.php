<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prenom', TextType::class, [
            'label' => 'Prénom*',
            'required' => true,
            'attr' => [
                'value' => 'Stewie'
            ]
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom*',
            'required' => true,
            'attr' => [
                'value' => 'Griffin'
            ]
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone*',
            'required' => true,
            'attr' => [
                'value' => '444719'
            ]
        ])
        ->add('nb_personnes', NumberType::class, [
            'label' => 'Nombre de personnes*',
            'required' => true,
            'attr' => [
                'value' => 1
            ]
        ])
        ->add('date_debut', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                // 'min' => ( new \DateTime() )->format('Y-m-d H:i:s')
            ],
            'label' => 'Date de début*',
            'data' => new \DateTime()
        ])
        ->add('date_fin', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Date de fin*'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
