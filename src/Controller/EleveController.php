<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Form\EleveType;
use App\Repository\EleveRepository;
use App\Utilities\GestionEleve;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/eleve")
 */
class EleveController extends AbstractController
{
    private $gestionEleve;
    private $eleveRepository;
    private $em;

    public function __construct(GestionEleve $gestionEleve, EleveRepository $eleveRepository, EntityManagerInterface $em)
    {
        $this->gestionEleve = $gestionEleve;
        $this->eleveRepository = $eleveRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="eleve_index", methods={"GET"})
     */
    public function index(EleveRepository $eleveRepository): Response
    {
        return $this->render('eleve/index.html.twig', [
            'eleves' => $eleveRepository->findBy([],['nom'=>"ASC",'prenoms'=>"ASC"]),
        ]);
    }

    /**
     * @Route("/new", name="eleve_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $eleve = new Eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Verification de la non existence de l'eleve dans le systeme
            $verif = $this->eleveRepository->findOneBy([
                'nom' => $eleve->getNom(),
                'prenoms' => $eleve->getPrenoms(),
                'dateNaissance' => $eleve->getDateNaissance(),
                'lieuNaissance' => $eleve->getLieuNaissance(),
                'nomParent' => $eleve->getNomParent(),
                'contactParent' => $eleve->getContactParent()
            ]);
            if ($verif){
                $this->addFlash('danger', "Echèc, cet élève existe déjà dans le système");
                return  $this->redirectToRoute('eleve_new');
            }

            $matricule =  $this->gestionEleve->matricule();
            $eleve->setMatricule($matricule);
            $entityManager->persist($eleve);
            $entityManager->flush();

            $this->addFlash('success', "L'élève a été enregistré avec succès");

            return $this->redirectToRoute('inscription_new',['eleve'=>$eleve->getId()]);
        }

        return $this->render('eleve/new.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="eleve_show", methods={"GET"})
     */
    public function show(Eleve $eleve): Response
    {
        $annee= $this->gestionEleve->annee();
        $inscription = $this->em->getRepository("App:Inscription")->findOneBy([
            'eleve'=>$eleve->getId(),
            'annee' => $annee
        ]);
        //dd($inscription);
        return $this->render('eleve/show.html.twig', [
            'eleve' => $eleve,
            'bordereaux'=> $this->gestionEleve->bordereau($eleve->getId()),
            'annee' => $annee,
            'inscription' => $inscription
        ]);
    }

    /**
     * @Route("/{id}/edit", name="eleve_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Eleve $eleve): Response
    {
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('eleve_index');
        }

        return $this->render('eleve/edit.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="eleve_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Eleve $eleve): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eleve->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eleve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('eleve_index');
    }
}
