<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Espace;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // Supprimer les réservations d'un utilisateur avant de supprimer un utilisateur
    public function supprimerUtilisateurDeReservation(User $user): void
    {
        $reservations = $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        
        foreach ($reservations as $reservation) {
            $this->_em->remove($reservation);
        }
        
        $this->_em->flush();
    }

    // Récupère les réservations à venir d'un espace
    public function findDatesReservees(?Espace $espace): array
    {
        $now = new \DateTime();
        $confirmee = 'CONFIRMEE';

        return $this->createQueryBuilder('r')
            ->andWhere('r.espace = :espace')
            ->andWhere('r.date_debut > :now')
            ->andWhere('r.statut = :confirmee' )
            ->setParameter('now', $now)
            ->setParameter('espace', $espace)
            ->setParameter('confirmee', $confirmee)
            ->getQuery()
            ->getResult()
        ;
    }
// Commenter ici !!!!!!
    public function findEspacesReserves(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin)
    {
    return $this->createQueryBuilder('r')

        ->where('r.espace = :espace')
        ->andWhere('r.date_debut <= :date_fin')
        ->andWhere('r.date_fin >= :date_debut')
        ->andWhere('r.adresseFacturation IS NOT NULL')
        ->setParameter('espace', $espace)
        ->setParameter('date_debut', $dateDebut)
        ->setParameter('date_fin', $dateFin)
        ->getQuery()
        ->getResult();

    }

    // Récupère les réservations en cours d'un utilisateur
    public function findReservationEnCours(User $user): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.date_debut < :now')
            ->andWhere('r.date_fin > :now')
            ->setParameter('now', $now)
            ->setParameter('user', $user)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
    ;
    }

    public function findReservationsPassees(User $user): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.date_fin < :now')
            ->setParameter('now', $now)
            ->setParameter('user', $user)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère les réservations à venir d'un utilisateur
    public function findReservationsAVenir(User $user): array
    {
        $now = new \DateTime();
        $confirmee = 'CONFIRMEE';

        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.date_debut > :now')
            ->andWhere('r.statut = :confirmee' )
            ->setParameter('now', $now)
            ->setParameter('user', $user)
            ->setParameter('confirmee', $confirmee)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère les réservations non confirmées d'un utilisateur
    public function findReservationsNonConfirmees(User $user): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.date_fin < :now ')
            ->andWhere('r.adresseFacturation IS NULL')
            ->setParameter('now', $now)
            ->setParameter('user', $user)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations en cours (tous les utilisateurs confondus, afin de les afficher dans l'administration)
    public function findToutesReservationsEnCours(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.adresseFacturation IS NOT NULL')
            ->andWhere('r.date_debut < :now')
            ->andWhere('r.date_fin > :now ')
            ->setParameter('now', $now)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations à venir (tous les utilisateurs confondus, afin de les afficher dans l'administration)
    public function findToutesReservationsAVenir(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.adresseFacturation IS NOT NULL')
            ->andWhere('r.date_debut > :now')
            ->setParameter('now', $now)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations passées (tous les utilisateurs confondus, afin de les afficher dans l'administration et de permettre le téléchargement du justificatif)
    public function findToutesReservationsPassees(): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.date_fin < :now')
            ->setParameter('now', $now)
            ->orderBy('r.date_fin', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations annulées
    public function findReservationsAnnulees(): array
    {
        $annulee = 'ANNULEE';

        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :annulee' )
            ->setParameter('annulee', $annulee)
            ->orderBy('r.date_debut', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations à rembourser
    public function findReservationsARembourser(): array
    {
        $aRembourser = 'A REMBOURSER';

        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :aRembourser' )
            ->setParameter('aRembourser', $aRembourser)
            ->orderBy('r.date_debut', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Récupère toutes les réservations à rembourser partiellement
    public function findReservationARembourserPartiellement(): array
    {
        $aRembourser = 'A REMBOURSER PARTIELLEMENT';

        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :aRembourser' )
            ->setParameter('aRembourser', $aRembourser)
            ->orderBy('r.date_debut', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

}
