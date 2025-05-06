<?php
namespace App\Controller;

use App\Form\Type\TaskType;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class TaskController extends AbstractController
{
    #[Route('/task/index', name: 'task_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', ['tasks' => $tasks]);
    }
    #[Route('/task/new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');
        }
        return $this->render('task/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/task/{id}', name: 'task_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');

        }
        return $this->render('task/update.html.twig', ['form' => $form]);
    }

    #[Route('/task/delete/{id}', name: 'task_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id){
        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }
}