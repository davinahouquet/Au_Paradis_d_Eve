<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Reservation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            // 'attr' => [
            //     'value' => 'Stewie'
            // ]
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom*',
            'required' => true,
            // 'attr' => [
            //     'value' => 'Griffin'
            // ]
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone*',
            'required' => true,
            'attr' => [
                // 'value' => '444719'
            ]
        ])
        ->add('nb_personnes', NumberType::class, [
            'label' => 'Nombre de personnes*',
            'required' => true,
            'attr' => [
                'min' => 1
            ],
        ])
        ->add('date_debut', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('d-m-Y'),
            ],
            'label' => 'Date de début*',
            'data' => (new \DateTime())->add(new \DateInterval('P1D')), // La date du jour + 1 jour
            // 'html5' => false
        ])
        ->add('date_fin', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('d-m-Y'),
            ],
            'label' => 'Date de fin*',
            'data' => (new \DateTime())->add(new \DateInterval('P3D')), //2 jours minimum pour réserver
            // 'html5' => false
        ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
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
