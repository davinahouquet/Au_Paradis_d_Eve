<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column]
    private ?int $nb_personnes = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\GreaterThan("today", message="La date de début doit être postérieure à aujourd'hui")
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
    * @Assert\GreaterThan(
    *     propertyPath="date_debut",
    *     message="La date de fin doit être postérieure à la date de début"
    * )
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column (nullable: true)]
    private ?float $prixTotal = null;

    #[ORM\Column(nullable: true)]
    private ?array $options = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;



    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Espace $espace = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseFacturation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => 'CURRENT_TIMESTAMP'], nullable: true)]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function getDateReservationFr(): ?string
    {
        return $this->dateReservation->format("d-m-Y");
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nb_personnes;
    }

    public function setNbPersonnes(int $nb_personnes): static
    {
        $this->nb_personnes = $nb_personnes;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function getDateDebutFr(): ?string{
        return $this->date_debut->format("d-m-Y");
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function getDateFinFr(): ?string{
            return $this->date_fin->format("d-m-Y");
        }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }
// Pour obtenir la durée du séjour (en nb de jours)
    public function getDuree(): ?int
    {
        $difference = $this->date_debut->diff($this->date_fin);
        return $difference->days;
    
    }

    // Retourne le résultat du calcul
    public function getPrixTotal(): ?float
    {
        $prixTotal = $this->calculerPrixTotal();

    return $prixTotal;
    }

    // Calcule le prix total en fonctiond e la durée du séjour
    public function calculerPrixTotal(): ?float
{
    if ($this->espace && $this->date_debut && $this->date_fin) {
        $difference = $this->date_debut->diff($this->date_fin);
        $duree = $difference->days;

        // Vérifiez que l'objet Espace est défini
        if ($this->espace) {
            $prixChambre = $this->espace->getPrix();
            $prixTotal = $prixChambre * $duree;
            return $prixTotal;
        }
    }

    return null;
}

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(?string $avis): static
    {
        $this->avis = $avis;

        return $this;
    }

    public function getUserReservations(): ?User
    {
        return $this->user_reservations;
    }

    public function setUserReservations(?User $user_reservations): static
    {
        $this->user_reservations = $user_reservations;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresseFacturation(): ?string
    {
        return $this->adresseFacturation;
    }

    public function setAdresseFacturation(?string $adresseFacturation): static
    {
        $this->adresseFacturation = $adresseFacturation;

        return $this;
    }

    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(string $facture): static
    {
        $this->facture = $facture;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
