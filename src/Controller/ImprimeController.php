<?php

namespace App\Controller;

use App\Entity\Versement;
use App\Utilities\GestionEleve;
use App\Utilities\GestionImpression;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
