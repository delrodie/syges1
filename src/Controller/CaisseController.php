<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Versement;
use App\Form\SearchVersementType;
use App\Utilities\GestionEleve;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @ROute("/caisse")
 */
class CaisseController extends AbstractController
{
    private $gestionEleve;

    public function __construct(GestionEleve $gestionEleve)
    {
        $this->gestionEleve = $gestionEleve;
    }

    /**
     * @Route("/", name="caisse_versment", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $annee = $this->gestionEleve->annee();

        $versement = new Versement();
        $form = $this->createForm(SearchVersementType::class, $versement);
        $form->handleRequest($request); //dd($form);

        if ($form->isSubmitted() && $form->isValid()){
            $classe = $versement->getClasse();
            $debut = $versement->getNumero();
            $fin = $versement->getDate();

            $classeLibelle = null;
            $bouton_actif = false;

            if ($classe && $debut && $fin){
                $versements = $this->getDoctrine()->getRepository(Versement::class)->findBySearch($classe->getId(), $debut, $fin);
                $classeLibelle = $classe->getLibelle();
                $bouton_actif = true;
            }elseif (!$classe && $debut && $fin){
                $versements = $this->getDoctrine()->getRepository(Versement::class)->findBySearch(null,$debut,$fin);
                $bouton_actif = true;
            }else{
                return $this->redirectToRoute('caisse_versment');
            }

            return $this->render('caisse/versement.html.twig',[
                'form' => $form->createView(),
                'versements' => $versements,
                'classes' => $this->getDoctrine()->getRepository(Classe::class)->findAll(),
                'annee' => $annee,
                'classe' => $classeLibelle,
                'debut' => $debut,
                'fin' => $fin,
                'bouton_actif' => $bouton_actif
            ]);
        }

        return $this->render('caisse/index.html.twig', [
            'form' => $form->createView(),
            'versements' => $this->getDoctrine()->getRepository(Versement::class)->findByAnnee($annee),
            'classes' => $this->getDoctrine()->getRepository(Classe::class)->findAll(),
            'annee' => $annee,
            'classe' => null,
        ]);
    }

    /**
     * @Route("/versment", name="caisse_imprime_versement", methods={"GET","POST"})
     */
    public function versement(Request $request): Response
    {
        $req_classe = $request->get('search_classe');
        $req_debut = $request->get('search_debut');
        $req_fin = $request->get('search_fin');

        if ($req_classe && $req_debut && $req_fin){
            $classe = $this->getDoctrine()->getRepository(Classe::class)->findOneBy(['libelle'=>$req_classe]);
            $versements = $this->getDoctrine()->getRepository(Versement::class)->findBySearch($classe->getId(), $req_debut, $req_fin);
            $classeLibelle = $classe->getLibelle();
        }elseif (!$req_classe && $req_debut && $req_fin){
            $versements = $this->getDoctrine()->getRepository(Versement::class)->findBySearch(null,$req_debut,$req_fin);
            $classeLibelle = true;
        }else{
            return $this->redirectToRoute('caisse_versment');
        }

        $total = 0; $verse = 0; $restant = 0; $i = 0; //dd($versements);
        foreach ($versements as $versement) {

            $verse = $verse + $versement->getVerse();
            $restant = $restant + $versement->getRestant();
        } //dd($verse);

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

        // Vérification du nombre de ligne de versement
        $quotien = (int) (count($versements) / 30);
        $reste = count($versements) % 30; //dd($quotien);
        if ($quotien > 1){
            $nombre_ligne = $quotien;
        }else{
            $nombre_ligne = 1;
        }//dd($nombre_ligne);
        //dd($versements);

        $html =  $this->render('imprime/caisse_versement.html.twig', [
            'versements' => $versements,
            'tot_verse' => $verse,
            'tot_reste' => $restant,
            'nombre_ligne' => $nombre_ligne,
            'debut' => $req_debut,
            'fin' => $req_fin
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'liste-versment:'.$versement->getNumero().'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }
}
