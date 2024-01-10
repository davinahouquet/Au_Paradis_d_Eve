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
    
    public function uploadImages(array $imageFiles, string $altImage, $espace)
    {
        foreach ($imageFiles as $imageFile) {
            $newFileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
            $imageFile->move($this->parameterBag->get('images_directory'), $newFileName);
            $image = new Image();
            $image->setLienImage($newFileName);
        //Cette ligne est spécifique à l’upload dans un espace.. Pur gérer l’upload d’une autre entité, il faudrait modifier ça (ici avec une condition pas propre, faire évoluer le service..propre)
            $image->setEspace($espace);
            $image->setAltImage($altImage);
            $espace->addImage($image);
            $this->entityManager->persist($image);
            $this->entityManager->persist($espace);
        }
        $this->entityManager->flush();
    }
}