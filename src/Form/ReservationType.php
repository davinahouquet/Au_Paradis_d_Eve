<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ReservationType extends AbstractType
{
    private $reservationRepository;

    // Injectez le ReservationRepository dans le constructeur
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('prenom', TextType::class, [
            'label' => 'Prénom*',
            'required' => true,
            // 'attr' => [
            //     'value' => 'Stewie'
            // ]
        ])
        ->add('nom', TextType::class, [
            'label' => 'Nom*',
            'required' => true,
            // 'attr' => [
            //     'value' => 'Griffin'
            // ]
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone*',
            'required' => true,
            'attr' => [
                // 'value' => '444719'
            ]
        ])
        ->add('nb_personnes', NumberType::class, [
            'label' => 'Nombre de personnes*',
            'required' => true,
            'attr' => [
                'min' => 1
            ],
        ])
        ->add('date_debut', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('Y-m-d'), // Format ISO 8601
            ],
            'label' => 'Date de début*',
            'data' => (new \DateTime())->add(new \DateInterval('P1D')),
        ])
        ->add('date_fin', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('Y-m-d'), // Format ISO 8601
            ],
            'label' => 'Date de fin*',
            'data' => (new \DateTime())->add(new \DateInterval('P3D')),
        ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ])
            
        // ;
        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $espace =  $data->getEspace();

            if ($data instanceof Reservation) {
                // Récup dates déjà réservées depuis repo
                $datesReservees = $this->reservationRepository->findDatesReservees($espace);

                // Attribut "disabled" aux options de date déjà réservées
                $disabledDates = [];
                foreach ($datesReservees as $dateReservee) {
                    $disabledDates[] = $dateReservee->getDateDebut()->format('Y-m-d');
                }

                $form->add('date_debut', DateType::class, [
                    'widget' => 'single_text',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('Y-m-d'),
                        'data-indisponibles' => json_encode($disabledDates), // Ajoute dates indisponibles comme attribut data
                    ],
                    'label' => 'Date de début*',
                    'data' => (new \DateTime())->add(new \DateInterval('P1D')),
                ]);
            }
        });
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     if ($data instanceof Reservation) {
        //         // Récup dates indisponibles depuis repository ou service
        //         $indisponibilites = $this->reservationRepository->findIndisponibilites($data->getEspace());

        //         // Ajoute attribut "disabled" aux options de date indisponibles
        //         $disabledDates = [];
        //         foreach ($indisponibilites as $indisponibilite) {
        //             $disabledDates[] = $indisponibilite->getDateDebut()->format('Y-m-d');
        //         }

        //         $form->add('date_debut', DateType::class, [
        //             'widget' => 'single_text',
        //             'required' => true,
        //             'attr' => [
        //                 'class' => 'form-control',
        //                 'min' => (new \DateTime())->add(new \DateInterval('P1D'))->format('Y-m-d'),
        //                 'data-indisponibles' => json_encode($disabledDates), // Ajoute dates indisponibles comme attribut data
        //             ],
        //             'label' => 'Date de début*',
        //             'data' => (new \DateTime())->add(new \DateInterval('P1D')),
        //         ]);
        //     }
        // });
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
