<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('id_sortie', HiddenType::class)
        ->add('nom_sortie', TextType::class, [
            'label' => 'Titre de la sortie',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('description_sortie', TextareaType::class, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control'], 
        ])
        ->add('tarif_sortie', NumberType::class, [
            'label' => 'Tarif approximatif',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('image_sortie', TextType::class, [
            'label' => 'Image',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'btn btn-success'], 
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
