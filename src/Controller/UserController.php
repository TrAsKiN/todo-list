<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: [Request::METHOD_GET])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(
        UserRepository $repository
    ): Response
    {
        return $this->render('user/list.html.twig', ['users' => $repository->findAll()]);
    }

    #[Route('/users/create', name: 'user_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(
        Request $request,
        UserPasswordHasherInterface $encoder,
        UserRepository $repository
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $repository->save($user, true);

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Security("is_granted('ROLE_ADMIN') or is_granted('IS_ME', userToEdit)")]
    public function edit(
        User $userToEdit,
        Request $request,
        UserPasswordHasherInterface $encoder,
        UserRepository $repository
    ): Response {
        $form = $this->createForm(UserType::class, $userToEdit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->hashPassword($userToEdit, $userToEdit->getPassword());
            $userToEdit->setPassword($password);

            $repository->save($userToEdit, true);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $userToEdit]);
    }
}
