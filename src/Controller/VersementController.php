<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Versement;
use App\Form\VersementType;
use App\Repository\VersementRepository;
use App\Utilities\GestionEleve;
use App\Utilities\GestionImpression;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/versement")
 */
class VersementController extends AbstractController
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
     * @Route("/", name="versement_index", methods={"GET"})
     */
    public function index(VersementRepository $versementRepository): Response
    {
        $annee = $this->gestionEleve->annee();
        return $this->render('versement/index.html.twig', [
            'versements' => $versementRepository->findBy(['annee'=>$annee]),
            'annee' => $this->gestionEleve->annee()
        ]);
    }

    /**
     * @Route("/new-{eleve}", name="versement_new", methods={"GET","POST"})
     */
    public function new(Request $request, Eleve $eleve): Response
    {
        $annee = $this->gestionEleve->annee();
        $scolarite = $this->em->getRepository("App:Scolarite")->findOneBy(['eleve'=>$eleve->getId(), 'annee'=>$annee]);

        $versement = new Versement();
        $form = $this->createForm(VersementType::class, $versement, ['eleve'=>$eleve]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Verifier si l'eleve est inscrit pour l'année en cours
            $inscrit = $this->em->getRepository("App:Inscription")->findOneBy(['eleve'=>$eleve->getId(), 'annee'=>$annee]);
            if (!$inscrit){
                $this->addFlash('danger', "Echec: Il faudrait que l'élève fasse d'abord son inscription.");
                return $this->redirectToRoute('inscription_new',['eleve'=>$eleve->getId()]);
            }

            // Verifier que le montant versé est entier
            if (!is_int($versement->getVerse())){
                $this->addFlash('danger', "Le montant versé ne doit pas composté d'espace ni de virgule");
                return  $this->redirectToRoute('versement_new', ['eleve'=> $eleve->getId()]);
            }

            // Verifier que le montant versé n'est pas supérieur au montant restant
            $restant = $scolarite->getRestant() - $versement->getVerse();
            if ($restant < 0){
                $this->addFlash('danger', "Attention le montant versé est supérieur au montant restant.");
                return $this->redirectToRoute('versement_new', ['eleve'=>$eleve->getId()]);
            }

            // Generation du recu
            $recu = $this->gestionEleve->generationRecu(true);
            $versement->setNumero($recu);
            $versement->setAnnee($annee);
            $versement->setRestant($restant);
            $versement->setClasse($eleve->getClasse());

            $entityManager->persist($versement);
            $entityManager->flush();

            if (!$this->gestionEleve->scolarite(null, $versement->getId()));

            return $this->redirectToRoute('versement_show',['id'=>$versement->getId()]);
        }

        // Les query
        $inscription = $this->em->getRepository("App:Inscription")->findOneBy(['eleve'=>$eleve->getId()]);

        return $this->render('versement/new.html.twig', [
            'versement' => $versement,
            'form' => $form->createView(),
            'inscription' => $inscription,
            'eleve' => $eleve,
            'scolarite' => $scolarite
        ]);
    }

    /**
     * @Route("/{id}", name="versement_show", methods={"GET"})
     */
    public function show(Versement $versement): Response
    {
        $eleve = $this->em->getRepository("App:Eleve")->findOneBy(['id'=>$versement->getEleve()->getId()]);
        $scolarite = $this->em->getRepository('App:Scolarite')->findOneBy([
            'eleve'=>$eleve->getId(),
            'annee' => $this->gestionEleve->annee()
        ]);

        return $this->render('versement/show.html.twig', [
            'versement' => $versement,
            'eleve' => $eleve,
            'scolarite' => $scolarite,
            'montant_lettre' => $this->gestionImpression->nombre_en_lettre($versement->getVerse())
        ]);
    }

    /**
     * @Route("/{id}/edit", name="versement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Versement $versement): Response
    {
        $annee = $this->gestionEleve->annee();
        $scolarite = $this->em->getRepository("App:Scolarite")->findOneBy(['eleve'=>$versement->getEleve()->getId(), 'annee'=>$annee]);

        $form = $this->createForm(VersementType::class, $versement, ['eleve'=>$versement->getEleve()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Verifier que le montant versé est entier
            if (!is_int($versement->getVerse())){
                $this->addFlash('danger', "Le montant versé ne doit pas composté d'espace ni de virgule");
                return  $this->redirectToRoute('versement_new', ['eleve'=> $versement->getEleve()->getId()]);
            }

            // Verifier que le montant versé n'est pas supérieur au montant restant
            $ancien_montant_verse = $request->get('ancien_montant'); //dd($ancien_montant_verse);
            $ancien_restant = $scolarite->getRestant() + $ancien_montant_verse;
            $restant = $ancien_restant - $versement->getVerse();
            if ($restant < 0){
                $this->addFlash('danger', "Attention le montant versé est supérieur au montant restant.");
                return $this->redirectToRoute('versement_new', ['eleve'=>$versement->getEleve()->getId()]);
            }
            $versement->setRestant($restant); //dd($versement);
            $this->getDoctrine()->getManager()->flush();

            // Mise a jour de la table scolarité
            $total_verse = $scolarite->getTotal() - $versement->getRestant();
            $this->gestionEleve->scolariteUpdated(
                $versement->getEleve()->getId(),
                $this->gestionEleve->annee(),
                $scolarite->getTotal(),
                $total_verse,
                $versement->getRestant()
            );

            return $this->redirectToRoute('versement_show',['id'=>$versement->getId()]);
        }

        // Les query
        $inscription = $this->em->getRepository("App:Inscription")->findOneBy(['eleve'=>$versement->getEleve()->getId()]);

        return $this->render('versement/edit.html.twig', [
            'versement' => $versement,
            'form' => $form->createView(),
            'inscription' => $inscription,
            'eleve' => $versement->getEleve(),
            'scolarite' => $scolarite
        ]);
    }

    /**
     * @Route("/{id}", name="versement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Versement $versement): Response
    {
        $eleve = $versement->getEleve();
        if ($this->isCsrfTokenValid('delete'.$versement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            // Mise a jour de la table scolarite
            $scolarite = $this->em->getRepository("App:Scolarite")->findOneBy(['eleve'=>$eleve->getId(), 'annee'=>$versement->getAnnee()]);
            if (!$scolarite){
                $this->addFlash('danger', "Echec de la suppression du versement. Veuillez reprendre ou contacter l'administrateur");
                return $this->redirectToRoute('versement_edit',['id'=>$versement->getId()]);
            }

            $verse = $scolarite->getVerse() - $versement->getVerse();
            $reste = $scolarite->getRestant() + $versement->getVerse();

            $scolarite->setRestant($reste);
            $scolarite->setVerse($verse);

            $entityManager->remove($versement);
            $entityManager->flush();

            $this->addFlash('success', "Le versement de l'élève a été supprimé avec succès.");
        }

        return $this->redirectToRoute('eleve_show',['id'=>$eleve->getId()]);
    }
}
