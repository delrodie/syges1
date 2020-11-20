<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\InscriptionRepository;
use App\Utilities\GestionEleve;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractController
{
    private $classeRepository;
    private $gestionEleve;
    private $inscriptionReposiroty;
    private $eleveRepository;

    public function __construct(ClasseRepository $classeRepository, GestionEleve $gestionEleve, InscriptionRepository $inscriptionRepository, EleveRepository $eleveRepository)
    {
        $this->classeRepository = $classeRepository;
        $this->gestionEleve = $gestionEleve;
        $this->inscriptionReposiroty = $inscriptionRepository;
        $this->eleveRepository = $eleveRepository;
    }

    /**
     * @Route("/", name="inscription_index", methods={"GET"})
     */
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
            'annee' => $this->gestionEleve->annee()
        ]);
    }

    /**
     * @Route("/new-{eleve}", name="inscription_new", methods={"GET","POST"})
     */
    public function new(Request $request, Eleve $eleve): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription, ['eleve'=>$eleve]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $annee = $this->gestionEleve->annee();
            $recu = $this->gestionEleve->generationRecu();

            //verification de la non inscription de l'eleve dans l'année encours
            $verif_inscription = $this->inscriptionReposiroty->findOneBy(['eleve'=>$eleve, 'annee'=>$annee]);
            if ($verif_inscription){
                $this->addFlash('danger', "Cet élève est déjà inscrit cette année academique");
                return $this->redirectToRoute('inscription_new',['eleve'=>$eleve->getId()]);
            }

            // Verifier que le montant verse est entier
            if (!is_int($inscription->getVerse())){
                $this->addFlash('danger', "Le montant versé ne doit pas comporter d'espace,ni de virgule");
                return  $this->redirectToRoute('inscription_new',['eleve'=>$eleve->getId()]);
            }

            // gestion de la classe de l'eleve
            $class_req = $request->get('inscription_classe');
            $classe = $this->classeRepository->findOneBy(['id'=>$class_req]);
            $inscription->setClasse($classe);

            //Gestion de la scolarite
            $montant = $classe->getScolarite();
            $verse = $inscription->getVerse();
            $restant = $montant-$verse;

            if ($restant < 0){
                $this->addFlash('danger', "Attention le montant versé est superieur au montant de la scolarité");
                return $this->redirectToRoute('inscription_new',['eleve'=>$eleve->getId()]);
            }

            $inscription->setAnnee($annee);
            $inscription->setRestant($restant);
            $inscription->setNumero($recu);

            $entityManager->persist($inscription);
            $entityManager->flush();

            if (!$this->gestionEleve->scolarite($inscription->getId()));

            $this->addFlash('success', "Inscription effectuée avec succès");

            return $this->redirectToRoute('inscription_show',['id'=>$inscription->getId()]);
        }

        return $this->render('inscription/new.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
            'eleve'=> $eleve,
            'classes'=> $this->classeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="inscription_show", methods={"GET"})
     */
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
            'eleve' => $this->eleveRepository->findOneBy(['id'=>$inscription->getEleve()->getId()])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="inscription_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Inscription $inscription): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription, ['eleve'=>$inscription->getEleve()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Verifier que le montant verse est entier
            if (!is_int($inscription->getVerse())){
                $this->addFlash('danger', "Le montant versé ne doit pas comporter d'espace,ni de virgule");
                return  $this->redirectToRoute('inscription_new',['eleve'=>$inscription->getEleve()->getId()]);
            }

            // gestion de la classe de l'eleve
            $class_req = $request->get('inscription_classe');
            $classe = $this->classeRepository->findOneBy(['id'=>$class_req]);
            $inscription->setClasse($classe);

            //Gestion de la scolarite
            $montant = $classe->getScolarite();
            $verse = $inscription->getVerse();
            $restant = $montant-$verse;

            if ($restant < 0){
                $this->addFlash('danger', "Attention le montant versé est superieur au montant de la scolarité");
                return $this->redirectToRoute('inscription_new',['eleve'=>$inscription->getEleve()->getId()]);
            }

            $inscription->setRestant($restant);

            $em->flush();
            $total = $inscription->getVerse()+$inscription->getRestant();
            $this->gestionEleve->scolariteUpdated($inscription->getEleve()->getId(),$inscription->getAnnee(),$total,$inscription->getVerse(),$inscription->getRestant());

            $this->addFlash('success', "Inscription modifiée avec succès");

            return $this->redirectToRoute('inscription_show',['id'=>$inscription->getId()]);
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
            'eleve' => $inscription->getEleve(),
            'classes' => $this->classeRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="inscription_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Inscription $inscription): Response
    {
        $eleve = $inscription->getEleve()->getId();
        if ($this->isCsrfTokenValid('delete'.$inscription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            //Supression de la scolarite
            if (!$this->gestionEleve->scolariteDeleted($inscription->getEleve()->getId(),$inscription->getAnnee())){
                $this->addFlash('danger',"Echec de la suppression de l'élève. Veuillez contacter l'administrateur");
                $this->redirectToRoute('inscription_edit',['id'=>$inscription->getId()]);
            }

            $entityManager->remove($inscription);
            $entityManager->flush();

            $this->addFlash('success', "L'inscription de l'élève a été supprimée avec succès!");
        }

        return $this->redirectToRoute('eleve_show',['id'=>$eleve]);
    }
}
