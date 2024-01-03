<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Espace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('lien_image', FileType::class, [
            //     'label' => 'Choisir une image',
            // ])
            ->add('lien_image', FileType::class, [
                'label' => 'Choisir une image',
            ])
            ->add('alt_image', TextType::class, [
                'label' => 'Texte de remplacement'
            ])
            ->add('espace', EntityType::class, [
                'class' => Espace::class,
'choice_label' => 'id',
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
            'data_class' => Image::class,
        ]);
    }
}
