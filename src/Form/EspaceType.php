<?php

namespace App\Form;

use App\Entity\Espace;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EspaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_espace')
            ->add('taille')
            ->add('wifi')
            ->add('nb_places')
            ->add('prix')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'id',
            ])
            // ->add('images', ImageType::class, [
            //     'label' => 'Télécharger une image',
            //     'mapped' => 'false',
            //     'multiple' => true
            // ])
            ->add('images', FileType::class, [
                'label' => 'Télécharger une image',
                'mapped' => 'false',
                'multiple' => true
            ])
            // ->add('images', CollectionType::class, [
            //     'entry_type' => FileType::class,
            //     'entry_options' => [
            //         'label' => 'Télécharger une image',
            //         'required' => false,
            //     ],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
            // ->add('alt_images', CollectionType::class, [
            //     'entry_type' => TextType::class,
            //     'entry_options' => [
            //         'label' => 'Alt Text for Image',
            //         'required' => false,
            //     ],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
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
