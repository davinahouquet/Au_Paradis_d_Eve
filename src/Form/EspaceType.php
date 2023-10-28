<?php

namespace App\Form;

use App\Entity\Espace;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EspaceType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_espace', TextType::class, [
                'label' => 'Nom de l\'espace',
            ])
            ->add('taille', NumberType::class, [
                'label' => 'Taille de l\'espace',
            ])
            ->add('wifi', CheckboxType::class, [
                'label' => 'WiFi disponible',
                'required' => false,
            ])
            ->add('nb_places', IntegerType::class, [
                'label' => 'Nombre de places',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class, 
                'attr' => [
                    'class' => 'form-control'
                ]
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
            'data_class' => Espace::class,
        ]);
    }
}
