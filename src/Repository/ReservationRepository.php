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

    public function findEspacesReserves(Espace $espace, \DateTime $dateDebut, \DateTime $dateFin)
    {
    return $this->createQueryBuilder('r')

        ->where('r.espace = :espace')
        // ->andWhere('r.user = :user')
        ->andWhere('r.date_debut <= :date_fin')
        ->andWhere('r.date_fin >= :date_debut')
        ->andWhere('r.adresseFacturation IS NOT NULL')
        ->setParameter('espace', $espace)
        ->setParameter('date_debut', $dateDebut)
        ->setParameter('date_fin', $dateFin)
        // ->setParameter('user', $user)
        ->getQuery()
        ->getResult();

    }

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

    public function findReservationsAVenir(User $user): array
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.adresseFacturation IS NOT NULL')
            ->andWhere('r.date_debut > :now')
            ->setParameter('now', $now)
            ->setParameter('user', $user)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
           ;
    }

    public function findReservationsNonConfirmees(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->andWhere('r.adresseFacturation IS NULL')
            ->setParameter('user', $user)
            ->orderBy('r.date_debut', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//     public function findLatestReservation(): ?Reservation
// {
//     return $this->createQueryBuilder('r')
//         ->orderBy('r.date_reservation', 'DESC')
//         ->setMaxResults(1)
//         ->getQuery()
//         ->getOneOrNullResult();
// }
//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
