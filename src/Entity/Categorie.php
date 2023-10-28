<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_categorie = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Espace::class)]
    private Collection $espaces;

    public function __construct()
    {
        $this->espaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(string $nom_categorie): static
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    /**
     * @return Collection<int, Espace>
     */
    public function getEspaces(): Collection
    {
        return $this->espaces;
    }

    public function addEspace(Espace $espace): static
    {
        if (!$this->espaces->contains($espace)) {
            $this->espaces->add($espace);
            $espace->setCategorie($this);
        }

        return $this;
    }

    public function removeEspace(Espace $espace): static
    {
        if ($this->espaces->removeElement($espace)) {
            // set the owning side to null (unless already changed)
            if ($espace->getCategorie() === $this) {
                $espace->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom_categorie;
    }
}
