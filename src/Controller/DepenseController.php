<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Form\DepenseType;
use App\Repository\DepenseRepository;
use App\Utilities\GestionEleve;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/depense")
 */
class DepenseController extends AbstractController
{
    private $gestionEleve;

    public function __construct(GestionEleve $gestionEleve)
    {
        $this->gestionEleve = $gestionEleve;
    }

    /**
     * @Route("/", name="depense_index", methods={"GET","POST"})
     */
    public function index(Request $request, DepenseRepository $depenseRepository): Response
    {
        $annee = $this->gestionEleve->annee();
        $debut = $request->get('search_debut');
        $fin = $request->get('search_fin');
        if ($debut && $fin){
            $depenses = $depenseRepository->findByPeriode($debut, $fin);
        }else{
            $depenses = $depenseRepository->findBy(['annee'=>$annee], ['date'=>"DESC"]);
        }

        $total = 0;
        foreach ($depenses as $depense){
            $total = $total + $depense->getMontant();
        }

        return $this->render('depense/index.html.twig', [
            'depenses' => $depenses,
            'annee' => $annee,
            'total' => $total
        ]);
    }

    /**
     * @Route("/new", name="depense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setAnnee($this->gestionEleve->annee());
            $entityManager->persist($depense);
            $entityManager->flush();

            $variable = [
                'type' => "DEPENSE",
                'montant' => $depense->getMontant(),
                'date' => $depense->getDate()
            ];

            if ($this->gestionEleve->operationCaisse($variable)){
                $depense->setCaisse(true);
                $entityManager->flush();
            }

            return $this->redirectToRoute('depense_new');
        }

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
            'depenses' => $this->getDoctrine()->getRepository(Depense::class)->findByPeriode(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_show", methods={"GET"})
     */
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="depense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Depense $depense): Response
    {
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index');
    }
}
