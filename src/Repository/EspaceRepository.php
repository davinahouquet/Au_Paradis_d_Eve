<?php

namespace App\Repository;

use App\Entity\Espace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Espace>
 *
 * @method Espace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espace[]    findAll()
 * @method Espace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espace::class);
    }

    public function findEspacesLouables(): array
    {
        $chambre = '1';
        $appartement = '11';
    
        return $this->createQueryBuilder('e')
            ->andWhere('e.categorie = :chambre OR e.categorie = :appartement')
            ->setParameter('chambre', $chambre)
            ->setParameter('appartement', $appartement)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findEspacesNonLouables(): array
    {
        $chambre = '1';
        $appartement = '11';

    return $this->createQueryBuilder('e')
        ->andWhere('e.categorie != :chambre')
        ->andWhere('e.categorie != :appartement')
        ->setParameter('chambre', $chambre)
        ->setParameter('appartement', $appartement)
        ->orderBy('e.id', 'ASC')
        ->getQuery()
        ->getResult()
    ;
    }

    public function findByCategorie($categorieId): array
    {
    return $this->createQueryBuilder('e')
        ->andWhere('e.categorie = :categorieId')
        ->setParameter('categorieId', $categorieId)
        ->orderBy('e.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
    }
//    /**
//     * @return Espace[] Returns an array of Espace objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Espace
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
