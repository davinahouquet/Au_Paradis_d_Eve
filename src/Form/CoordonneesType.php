<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CoordonneesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email*',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse*',
                'required' => true
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code postal*',
                'required' => true
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville*',
                'required' => true
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays*',
                'required' => true
            ])
            ->add('souvenir', CheckboxType::class, [
                'label'    => 'Se souvenir de mon adresse pour de futures réservations',
                'required' => false,
            ])
            ->add('valider', SubmitType::class);
        ;
    }

   
}
