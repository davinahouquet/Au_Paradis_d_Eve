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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prenom', TextType::class, [
            'label' => 'Prénom*',
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom*',
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone*',
        ])
        ->add('nb_personnes', NumberType::class, [
            'label' => 'Nombre de personnes*',
        ])
        ->add('date_debut', DateType::class, [
            'label' => 'Date de début*',
        ])
        ->add('date_fin', DateType::class, [
            'label' => 'Date de fin*',
        ])
        // ->add('prixTotal', NumberType::class, [
        //     'label' => 'Prix total',
        // ])
        // ->add('options', TextType::class, [
        //     'label' => 'Options',
        // ])
        // ->add('note', TextType::class, [
        //     'label' => 'Note',
        // ])
        // ->add('avis', TextType::class, [
        //     'label' => 'Avis',
        // ])
        // ->add('espace', EntityType::class, [
        //     'class' => Espace::class,
        //     'choice_label' => 'nomEspace', 
        //     'label' => 'Chambre',
        // ])
        ->add('prixTotal', MoneyType::class, [
            'label' => 'Prix total',
            'disabled' => true, // Rendre le champ en lecture seule
            'currency' => 'EUR', // Devise, à adapter en fonction de votre besoin
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
