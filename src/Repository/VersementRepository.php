<?php

namespace App\Repository;

use App\Entity\Versement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Versement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Versement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Versement[]    findAll()
 * @method Versement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Versement::class);
    }

    public function findByAnnee($annee, $classe = null)
    {
        $q = $this->createQueryBuilder('v')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('v.eleve', 'e')
            ->leftJoin('v.classe', 'c')
            ->where('v.annee = :annee');

        if ($classe){
            $q->andWhere('c.libelle = :classe')
                ->setParameters([
                    'annee' => $annee,
                    'classe' => $classe
                ]);
        }else{
            $q->setParameter('annee', $annee);
        }

        return $q->getQuery()->getResult();

    }

    public function findBySearch($classe = null, $debut = null, $fin = null)
    {
        $q = $this->createQueryBuilder('v')
            ->addSelect('e')
            ->addSelect('c')
            ->leftJoin('v.eleve', 'e')
            ->leftJoin('v.classe', 'c');
        if ($classe && $debut && $fin){
            $q->where('v.classe = :classe')
                ->andWhere('v.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'classe' => $classe,
                    'debut' => $debut,
                    'fin' => $fin
                ]);
        }elseif ($debut && $fin){
            $q->where('v.date BETWEEN :debut AND :fin')
                ->setParameters([
                    'debut' => $debut,
                    'fin' => $fin
                ]);
        }else{
            $q->where('v.date = :date')
                ->setParameter('date', date('Y-m-d', time()));
        }

        return $q->getQuery()->getResult();
    }

    // /**
    //  * @return Versement[] Returns an array of Versement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Versement
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
