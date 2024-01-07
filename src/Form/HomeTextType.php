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
        ->add('maison_dhote', TextareaType::class, [
            'label' => 'Titre',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('reservation', TextareaType::class, [
            'label' => 'Ligne de texte',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('environnement', TextareaType::class, [
            'label' => 'Ligne de texte',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('pays_de_bitche', TextareaType::class, [
            'label' => 'Titre',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('description_pays_de_bitche', TextareaType::class, [
            'label' => 'Texte',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('mascotte', TextareaType::class, [
            'label' => 'Titre',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('description_mascotte', TextareaType::class, [
            'label' => 'Texte',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('telephone_fixe', TextType::class, [
            'label' => 'Téléphone Fixe',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('email', TextType::class, [
            'label' => 'Email',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Rue et N° de rue',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('cp', TextType::class, [
            'label' => 'Code postal',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('ville', TextType::class, [
            'label' => 'Ville',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'btn btn-success'],
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
