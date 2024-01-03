<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Option;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $pseudos = [
            "davina",
            "ricardo",
        ];
        $emails = [
            "davina@deglingo.fr",
            "ricardo@psycho.fr",
        ];
        $roles = [
            '["ROLE_ADMIN"]',
            '["ROLE_USER"]'
        ];
    

        // Liste de Users
        $usersList = [];
        // Création de 2 Users
        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                '123'
            ));
            $user->setPseudo($pseudos[$i]);
            $user->setEmail($emails[$i]);
            $user->setRoles([$roles]);
            $usersList[] = $user;
            $manager->persist($user);
        }

        $manager->persist($user);

        // Liste des Catégories
        $categoriesList = [];
        $categoriesNames = [
            'Chambre', 'Jardin', 'Terrasse', 'Piscine', 'Salle de bain', 'Salon', 'Aire de jeu', 'Cuisine', 'Garage', 'Parking', 'Logement entier', 'Salle à manger'
        ];

        for ($i = 0; $i < 12; $i++) {
            $categorie = new Categorie();
            $categorie->setNomCategorie($categoriesNames[$i]);
            $categoriesList[] = $categorie;
            $manager->persist($categorie);
        };

        $espaceNames = [
            'Chambre des Liilas', 'Chambre du Tournesol', 'Chambre du Temps'
        ];

        // Liste des Espaces
        $espacesList = [];
        // Création de 3 espaces
        for ($i = 0; $i < 3; $i++) {
            $espace = new Espace();
            $espace->setNomEspace($espaceNames[$i]);
            $espace->setTaille(rand(1,100));
            $espace->setWifi(rand(0,1));
            $espace->setNbPlaces(rand(1,10));
            $espace->setPrix(rand(1,200));
            shuffle($categoriesList);
            $espace->setCategorie($categoriesList[0]);
            $espacesList[] = $espace;
            $manager->persist($espace);
        }

        $optionsNames = [
            'Option 1', 'Option 2', 'Option 3' 
        ];


        // Liste des Options
        $optionsList = [];

        // Création de 3 options
        for ($i = 0; $i < 3; $i++) {
            $option = new Option();
            $option->setNom($optionsNames[$i]);
            $option->setTarif(rand(5,20));
            $option->setDescription('test description');
            $optionsList[] = $option;
            $manager->persist($option);
        }

        $manager->flush();
    }
}

