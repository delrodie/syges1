<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Inscription;
use App\Entity\Scolarite;
use App\Entity\Versement;
use App\Utilities\GestionEleve;
use App\Utilities\GestionImpression;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImprimeController
 * @Route("/imprime")
 */
class ImprimeController extends AbstractController
{

    private $em;
    private $gestionEleve;
    private $gestionImpression;

    public function __construct(EntityManagerInterface $em, GestionEleve $gestionEleve, GestionImpression $gestionImpression)
    {
        $this->em = $em;
        $this->gestionEleve = $gestionEleve;
        $this->gestionImpression = $gestionImpression;
    }

    /**
     * @Route("/", name="imprime_scolarite", methods={"GET", "POST"})
     */
    public function scolarite(Request $request)
    {
        $annee = $this->gestionEleve->annee();
        $classes = $this->getDoctrine()->getRepository(Classe::class)->findAll();

        $classe = $request->get('classe');
        if ($classe){
            //$classe = $this->getDoctrine()->getRepository(Classe::class)->findOneBy(['id'=>$search_class])->getLibelle();
            $scolarites = $this->getDoctrine()->getRepository(Scolarite::class)->findByAnneeAndClasse($annee, $classe);
            $garcon = 0; $fille = 0;
            $total = 0; $verse = 0; $reste = 0;
            foreach ($scolarites as $scolarite){
                $sexe = $scolarite->getEleve()->getSexe();
                if ($sexe === 'FILLE') $fille = $fille + 1;
                else $garcon = $garcon + 1;

                $total = $total + $scolarite->getTotal();
                $verse = $verse + $scolarite->getVerse();
                $reste = $reste + $scolarite->getRestant();
            }
        }else{
            return $this->redirectToRoute('etat_scolarite');
        }

        // O definit les options du PDF
        $pdfOptions = new Options();

        // Definiton de la police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $pdfOptions->setIsPhpEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->render('imprime/scolarite.html.twig',[
            'classe' => $classe,
            'classes' => $classes,
            'scolarites' => $scolarites,
            'garcon' => $garcon,
            'fille' => $fille,
            'tot_scolarite' => $total,
            'tot_verse' => $verse,
            'tot_reste' => $reste
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'scolarite-'.$classe.'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }

    /**
     * @Route("/versement-{id}", name="imprime_versement", methods={"GET"})
     */
    public function versement(Versement $versement): Response
    {
        $eleve = $this->em->getRepository("App:Eleve")->findOneBy(['id'=>$versement->getEleve()->getId()]);
        $scolarite = $this->em->getRepository('App:Scolarite')->findOneBy([
            'eleve'=>$eleve->getId(),
            'annee' => $this->gestionEleve->annee()
        ]);

        // O definit les options du PDF
        $pdfOptions = new Options();

        // Definiton de la police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html =  $this->render('imprime/versement.html.twig', [
            'versement' => $versement,
            'eleve' => $eleve,
            'scolarite' => $scolarite,
            'montant_lettre' => $this->gestionImpression->nombre_en_lettre($versement->getVerse())
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'Versement-recu-N°:'.$versement->getNumero().'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }

    /**
     * @Route("/inscription/{id}", name="imprimer_inscription", methods={"GET"})
     */
    public function inscription(Inscription $inscription)
    {
        // O definit les options du PDF
        $pdfOptions = new Options();

        // Definiton de la police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $dompdf->setHttpContext($context);

        $montant_lettre = $this->gestionImpression->nombre_en_lettre($inscription->getVerse());
        $html =  $this->render('imprime/inscription.html.twig', [
            'inscription' => $inscription,
            'eleve' => $this->em->getRepository(Eleve::class)->findOneBy(['id'=>$inscription->getEleve()->getId()]),
            'montant_lettre' => $montant_lettre
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'Inscription-recu-N°:'.$inscription->getNumero().'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }

    /**
     * @Route("/convocation/{classe}/1", name="imprime_convocation_classe", methods={"GET","POST"})
     */
    public function convocation($classe)
    {
        $annee = $this->gestionEleve->annee();
        $class = $this->em->getRepository(Classe::class)->findOneBy(['libelle'=>$classe]); //dd($class->getId());
        $scolarites = $this->em->getRepository(Scolarite::class)->findByAnneeAndClasse($annee, $classe);

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

        // O definit les options du PDF
        $pdfOptions = new Options();

        // Definiton de la police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $dompdf->setHttpContext($context);

        //dd($scolarites);
        $html = $this->render('imprime/convocation.html.twig',[
            'scolarites' => $scolarites,
            'annee' => $annee,
            'classe' => $class,
            'date_echeance' => $date_echeance,
            'date_jour' => $date_encours
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'Relance-'.$classe.'-du_'.$annee_encours.'-'.$mois_encours.'-'.$jour_encours.'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }


}
