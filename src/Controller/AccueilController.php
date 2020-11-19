<?php

namespace App\Controller;

use App\Utilities\GestionEleve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $em;
    private $gestionElelve;

    public function __construct(EntityManagerInterface $em, GestionEleve $gestionEleve)
    {
        $this->em = $em;
        $this->gestionElelve = $gestionEleve;
    }
    /**
     * @Route("/", name="app_ccueil")
     */
    public function index(): Response
    {
        $annee = $this->gestionElelve->annee();
        $inscrits = $this->em->getRepository("App:Inscription")->findByAnnee($annee);
        if (count($inscrits) === 0) $total_inscrit = 1;
        else $total_inscrit = count($inscrits);

        $sexes =['GARCON'=>'GARCON', 'FILLE'=>'FILLE'];
        foreach ($sexes as $sexe){
            $nombre = $this->em->getRepository("App:Inscription")->findBySexe($annee, $sexe);
            $total_sexe[$sexe] = count($nombre);
            $pourcentage[$sexe] = count($nombre) * 100 / $total_inscrit;
        }

        $versements = $this->em->getRepository('App:Versement')->findByAnnee($annee);
        $classes = $this->em->getRepository('App:Classe')->findAll();
        $i = 0;
        foreach ($classes as $classe){
            $nombre = $this->em->getRepository("App:Inscription")->findBy(['annee'=>$annee, 'classe'=>$classe->getId()]);
            $garcons = $this->em->getRepository("App:Inscription")->findBySexeAndClasse($annee,"GARCON",$classe->getId());
            $filles = $this->em->getRepository("App:Inscription")->findBySexeAndClasse($annee,"FILLE",$classe->getId());
            $effectifs[$i] = [
                'classe' => $classe->getLibelle(),
                'total' => count($nombre),
                'garcon' => count($garcons),
                'fille' => count($filles)
            ];
            $i = $i +1;
        }

        return $this->render('accueil/index.html.twig', [
            'total_inscrit' => count($inscrits),
            'inscrits' => $inscrits,
            'total_garcon' => $total_sexe['GARCON'],
            'total_fille' => $total_sexe['FILLE'],
            'pourcent_garcon' => $pourcentage['GARCON'],
            'pourcent_fille' => $pourcentage['FILLE'],
            'versements' => $versements,
            'classes' => $classes,
            'effectifs' => $effectifs
        ]);
    }
}
