<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lien_image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $alt_image = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Espace $espace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLienImage(): ?string
    {
        return $this->lien_image;
    }

    public function setLienImage(?string $lien_image): static
    {
        $this->lien_image = $lien_image;

        return $this;
    }

    public function getAltImage(): ?string
    {
        return $this->alt_image;
    }

    public function setAltImage(?string $alt_image): static
    {
        $this->alt_image = $alt_image;

        return $this;
    }

    public function getEspace(): ?Espace
    {
        return $this->espace;
    }

    public function setEspace(?Espace $espace): static
    {
        $this->espace = $espace;

        return $this;
    }
}
