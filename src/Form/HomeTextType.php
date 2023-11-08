<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HomeTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('maison_dhote', TextType::class, [
            'label' => 'Titre',
        ])
        ->add('reservation', TextType::class, [
            'label' => 'Ligne de texte',
        ])
        ->add('environnement', TextType::class, [
            'label' => 'Ligne de texte',
        ])
        ->add('pays_de_bitche', TextType::class, [
            'label' => 'Titre',
        ])
        ->add('description_pays_de_bitche', TextareaType::class, [
            'label' => 'Texte',
        ])
        ->add('mascotte', TextType::class, [
            'label' => 'Titre',
        ])
        ->add('description_mascotte', TextareaType::class, [
            'label' => 'Texte',
        ])
        ->add('telephone_fixe', TextType::class, [
            'label' => 'Téléphone Fixe',
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone',
        ])
        ->add('email', TextType::class, [
            'label' => 'Email',
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Rue et N° de rue',
        ])
        ->add('cp', TextType::class, [
            'label' => 'Code postal',
        ])
        ->add('ville', TextType::class, [
            'label' => 'Ville',
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
