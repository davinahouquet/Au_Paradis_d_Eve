<?php

namespace App\Services;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageUploadService{

    private $entityManager;
    private ParameterBagInterface $parameterBag;
    
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
    }
    
    // Upload d'images spécifiquement lié à l'entité Espace | Si souhait d'uploader dans une autre entité (option?), faire une nouvelle fonction
    public function uploadImages(array $imageFiles, string $altImage, $espace)
    {
        foreach ($imageFiles as $imageFile) {

            $newFileName = md5(uniqid()) . '.webp'; // Nouvelle extension en WebP
            // Convertir l'image en WebP
            $image = imagecreatefromstring(file_get_contents($imageFile->getPathname()));
            imagewebp($image, $this->parameterBag->get('images_directory') . '/' . $newFileName, 80); // 80 = qualité
            $image = new Image();
            $image->setLienImage($newFileName);
    
            $image->setEspace($espace);
            $image->setAltImage($altImage);
            $espace->addImage($image);
            $this->entityManager->persist($image);
            $this->entityManager->persist($espace);
        }
        $this->entityManager->flush();
    }
}