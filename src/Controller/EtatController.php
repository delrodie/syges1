<?php

namespace App\Controller;

use App\Utilities\GestionEleve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etat")
 */
class EtatController extends AbstractController
{
    private $em;
    private $gestionEleve;

    public function __construct(EntityManagerInterface $em, GestionEleve $gestionEleve)
    {
        $this->em = $em;
        $this->gestionEleve = $gestionEleve;
    }
    /**
     * @Route("/", name="etat_classe", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $annee = $this->gestionEleve->annee();
        $classes = $this->em->getRepository("App:Classe")->findAll();

        // Si une requete a été effectuée alors afficher la liste selon la requête sinon liste de la classe de CP1
        $search_class = $request->get('search_classe');
        if ($search_class){
            $classe = $this->em->getRepository("App:Classe")->findOneBy(['id'=>$search_class])->getLibelle();
            $eleves = $this->em->getRepository("App:Eleve")->findBy(['classe'=>$search_class, 'annee'=>$annee], ['nom'=>'ASC', 'prenoms'=>"ASC"]);
        }else{
            $classe = "CP1";
            $eleves = $this->em->getRepository("App:Eleve")->findBy(['classe'=>1, 'annee'=>$annee], ['nom'=>'ASC', 'prenoms'=>"ASC"]);
        }

        return $this->render('etat/index.html.twig', [
            'eleves' => $eleves,
            'annee' => $annee,
            'classe' => $classe,
            'classes' => $classes
        ]);
    }

    /**
     * @Route("/convocation", name="etat_convocation", methods={"GET","POST"})
     */
    public function convocation(Request $request)
    {
        $annee = $this->gestionEleve->annee();
        $classes = $this->em->getRepository("App:Classe")->findAll();

        // Si une requete a été effectuée alors afficher la liste selon la requête sinon liste de la classe de CP1
        $search_class = $request->get('search_classe');
        if ($search_class){
            $classe = $this->em->getRepository("App:Classe")->findOneBy(['id'=>$search_class])->getLibelle();
            $scolarites = $this->em->getRepository("App:Scolarite")->findByAnneeAndClasse($annee, $classe);
        }else{
            $classe = "CP1";
            $scolarites = $this->em->getRepository("App:Scolarite")->findByAnneeAndClasse($annee, $classe);
        } //dd($scolarites);

        return $this->render('etat/convocation.html.twig',[
            'scolarites' => $scolarites,
            'annee' => $annee,
            'classe' => $classe,
            'classes' => $classes
        ]);
    }

    /**
     * @Route("/convocation/{classe}", name="etat_convacation_relance", methods={"GET","POST"})
     */
    public function relance(Request $request, $classe)
    {
        $annee = $this->gestionEleve->annee();
        $class = $this->em->getRepository("App:Classe")->findOneBy(['libelle'=>$classe]); //dd($class->getId());
        $scolarites = $this->em->getRepository("App:Scolarite")->findByAnneeAndClasse($annee, $classe);

        $mois_encours = date('m');
        $annee_encours = date('Y');
        $jour_encours = date('d');

        if ($jour_encours >= 05){
            if ($mois_encours === 12) $mois = 01;
            else $mois = $mois_encours + 1;

            $date_echeance = '05/'.$mois.'/'.$annee_encours;
            $date_encours = $jour_encours.'/'.$mois_encours.'/'.$annee_encours;
        }else{
            if ($mois_encours === 12) $mois = 01;
            else $mois = $mois_encours;

            $date_echeance = '05/'.$mois.'/'.$annee_encours;
            $date_encours = $jour_encours.'/'.$mois_encours.'/'.$annee_encours;
        }

        return $this->render('etat/relance.html.twig',[
            'scolarites' => $scolarites,
            'annee' => $annee,
            'classe' => $class,
            'date_echeance' => $date_echeance,
            'date_jour' => $date_encours
        ]);
    }
}
