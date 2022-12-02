<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list', methods: [Request::METHOD_GET])]
    public function list(
        TaskRepository $repository
    ): Response {
        $anonymousTasks = null;
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $anonymousTasks = $repository->findBy(['owner' => [null]], ['title' => 'ASC']);
        }
        return $this->render('task/list.html.twig', [
            'tasks' => $repository->findBy(['owner' => $this->getUser()], ['title' => 'ASC']),
            'anonymous_tasks' => $anonymousTasks
        ]);
    }

    #[Route('/tasks/create', name: 'task_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(
        Request $request,
        TaskRepository $repository
    ): Response {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setOwner($this->getUser());
            $repository->save($task, true);

            $this->addFlash('success', "La tâche a été bien été ajoutée.");

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted(TaskVoter::MY_TASK, subject: 'task')]
    public function edit(
        Task $task,
        Request $request,
        TaskRepository $repository
    ): Response {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($task, true);

            $this->addFlash('success', "La tâche a bien été modifiée.");

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: [Request::METHOD_GET])]
    #[IsGranted(TaskVoter::MY_TASK, subject: 'task')]
    public function toggle(
        Task $task,
        TaskRepository $repository
    ): Response {
        $task->toggle(!$task->isDone());
        $repository->save($task, true);

        $this->addFlash('success', sprintf("La tâche %s a bien été marquée comme faite.", $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: [Request::METHOD_GET])]
    #[Security("is_granted('". TaskVoter::MY_TASK ."', task) or is_granted('". TaskVoter::ANONYMOUS_TASK ."', task)")]
    public function delete(
        Task $task,
        TaskRepository $repository
    ): Response {
        $repository->remove($task, true);

        $this->addFlash('success', "La tâche a bien été supprimée.");

        return $this->redirectToRoute('task_list');
    }
}
