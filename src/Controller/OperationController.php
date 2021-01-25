<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Versement;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use App\Utilities\GestionEleve;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation")
 */
class OperationController extends AbstractController
{
    private $gestionEleve;

    public function __construct(GestionEleve $gestionEleve)
    {
        $this->gestionEleve = $gestionEleve;
    }

    /**
     * @Route("/", name="operation_index", methods={"GET", "POST"})
     */
    public function index(Request $request, OperationRepository $operationRepository): Response
    {
        $annee = $this->gestionEleve->annee();
        $debut = $request->get('search_debut');
        $fin = $request->get('search_fin');
        if ($debut && $fin){
            $operations = $operationRepository->findByPeriode($debut, $fin);
        }else{
            $operations = $operationRepository->findBy(['annee'=>$annee], ['dateOperation'=>"DESC"]);
            $debut = false; $fin = null;
        }

        $entree = 0; $depense = 0;
        foreach ($operations as $operation){
            if ($operation->getType() === 'ENTREE') $entree = $entree + $operation->getMontant();
            else $depense = $depense + $operation->getMontant();
        }
        $solde = $entree - $depense;

        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
            'annee' => $annee,
            'entree'=> $entree,
            'depense' => $depense,
            'solde' => $solde,
            'debut' => $debut,
            'fin' => $fin
        ]);
    }

    /**
     * @Route("/new", name="operation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('operation_index');
        }else{
            $items = $this->getDoctrine()->getRepository(Versement::class)->getMontantGroupByDate();


        }

        return $this->render('operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operation_show", methods={"GET"})
     */
    public function show(Operation $operation): Response
    {
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="operation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Operation $operation): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operation_index');
        }

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Operation $operation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('operation_index');
    }
}
