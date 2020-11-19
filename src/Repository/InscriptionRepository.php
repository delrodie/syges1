<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    /**
     * Liste des inscrits par année
     *
     * @param $annee
     * @return int|mixed|string
     */
    public function findByAnnee($annee)
    {
        return $this->createQueryBuilder('i')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('i.eleve', 'e')
            ->leftJoin('i.classe', 'c')
            ->where('e.annee = :annee')
            ->orderBy('i.createdAt', "DESC")
            ->setParameter('annee', $annee)
            ->getQuery()->getResult()
            ;
    }

    public function findBySexe($annee, $sexe)
    {
        return $this->createQueryBuilder('i')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('i.eleve', 'e')
            ->leftJoin('i.classe', 'c')
            ->where('e.annee = :annee')
            ->andWhere('e.sexe = :sexe')
            ->orderBy('i.createdAt', "DESC")
            ->setParameters([
                'annee' => $annee,
                'sexe' => $sexe
            ])
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
