<?php

namespace App\Repository;

use App\Entity\Scolarite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scolarite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scolarite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scolarite[]    findAll()
 * @method Scolarite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScolariteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scolarite::class);
    }

    public function findByAnnee($annee)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('s.eleve', 'e')
            ->leftJoin('e.classe', 'c')
            ->where('s.annee = :annee')
            ->setParameter('annee', $annee)
            ->getQuery()->getResult()
            ;
    }

    /**
     * @param $annee
     * @param $classe
     * @return int|mixed|string
     */
    public function findByAnneeAndClasse($annee, $classe)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('s.eleve', 'e')
            ->leftJoin('e.classe', 'c')
            ->where('s.annee = :annee')
            ->andWhere('c.libelle = :classe')
            ->orderBy('e.nom', "ASC")
            ->addOrderBy('e.prenoms', "ASC")
            ->setParameters([
                'annee' => $annee,
                'classe' => $classe
            ])
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Scolarite[] Returns an array of Scolarite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scolarite
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
