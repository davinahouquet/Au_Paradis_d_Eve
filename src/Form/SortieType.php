<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('id_sortie', HiddenType::class)
        ->add('nom_sortie', TextType::class, [
            'label' => 'Nom de la sortie',
        ])
        ->add('description_sortie', TextType::class, [
            'label' => 'Description',
        ])
        ->add('tarif_sortie', NumberType::class, [
            'label' => 'Tarif approximatif',
        ])
        ->add('image_sortie', TextType::class, [
            'label' => 'Image',
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
