<?php

namespace App\Form;

use App\Entity\Espace;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EspaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 ->add('nom_espace', TextType::class, [
                'label' => 'Nom de l\'espace*',
                'attr' => ['class' => 'form-control']
            ])
            ->add('taille', NumberType::class, [
                'label' => 'Taille*',
                'attr' => ['class' => 'form-control']
            ])
            ->add('wifi', CheckboxType::class, [
                'label' => 'WiFi*',
                'attr' => ['class' => 'form-input'],
                'required' => false
            ])
            ->add('nb_places', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix*',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'espace (facultative)',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom_categorie',
                'attr' => ['class' => 'form-control']
            ])
            ->add('imageFiles', FileType::class, [
                'label' => 'Image(s)',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'help' => 'Formats autorisés : JPEG, PNG, WEBP | Taille maximale : 5 Mo',
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '5M', // Limite la taille à 5 Mo
                                'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                                'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, WEBP)',
                            ]),
                        ],
                    ]),
                ],
            ])
            ->add('altImage', TextareaType::class, [
                'label' => 'Texte de remplacement',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success']
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
