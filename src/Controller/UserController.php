<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\User;
use App\Form\ClasseType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    private $userRepository;
    private $em;
    private $passwordEncode;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordEncode = $passwordEncoder;
    }
    /**
     * @Route("/", name="admin_user_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Query role
            $query = $request->get('user_roles'); //dd($query);
            if ($query === '1') $role  = ["ROLE_ADMIN"];
            else $role = ["ROLE_USER"];
            $user->setRoles($role);

            $user->setPassword($this->passwordEncode->encodePassword($user, $user->getPassword())); //dd($user);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Utilisateur enregistré avec succès");

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findWithout(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{username}", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Query role
            $query = $request->get('user_roles'); //dd($query);
            if ($query === '1') $role  = ["ROLE_ADMIN"];
            else $role = ["ROLE_USER"];
            $user->setRoles($role);

            $user->setPassword($this->passwordEncode->encodePassword($user, $user->getPassword())); //dd($user);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Utilisateur modifié avec succès");

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findWithout(),
            'form' => $form->createView()
        ]);
    }
}
