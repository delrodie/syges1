<?php

namespace App\Controller;

use App\Entity\Operation;
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
 * Class ImprimeCaisseController
 * @Route("imprime/caisse")
 */
class ImprimeCaisseController extends AbstractController
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
     * @Route("/", name="imprime_caisse_operation", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $annee = $this->gestionEleve->annee();
        $debut = $request->get('search_debut');
        $fin = $request->get('search_fin'); //dd($debut);
        if ($debut && $fin){
            $operations = $this->getDoctrine()->getRepository(Operation::class)->findByPeriode($debut,$fin);
            $nombre = count($operations);
        }else{
            $operations = $this->getDoctrine()->getRepository(Operation::class)->findBy(['annee'=>$annee], ['dateOperation'=>"ASC"]);
            if ($operations){
                $nombre = count($operations);
                $debut = $operations[0]->getDateOperation();
                $fin = $operations[$nombre-1]->getDateOperation();
            }else{
                $debut = date('Y-m-d', time());
                $fin = date('Y-m-d', time());
            }

        }

        $entree = 0; $depense = 0; $solde = 0;
        foreach ($operations as $operation){
            if ($operation->getType() === 'ENTREE')
                $entree = $entree + $operation->getMontant();
            else
                $depense = $depense + $operation->getMontant();
        }
        $solde = $entree - $depense;


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

        // Vérification du nombre de ligne de versement
        $quotien = (int) (count($operations) / 30);
        $reste = count($operations) % 30; //dd($quotien);
        if ($quotien > 1){
            $nombre_ligne = $quotien;
        }else{
            $nombre_ligne = 1;
        }


        $html =  $this->render('imprime_caisse/index.html.twig', [
            'operations' => $operations,
            'entree' => $entree,
            'depense' => $depense,
            'solde' => $solde,
            'debut' => $debut,
            'fin' => $fin,
            'nombre_ligne' => $nombre_ligne
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère le nom du fichier
        $fichier = 'operations-du_'.$debut.'-au-'.$fin.'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier,[
            'Attachement' => false
        ]);

        return new Response();
    }
}
