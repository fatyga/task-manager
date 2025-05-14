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

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/task/new', name: 'task_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            
            $entityManager->persist($task);
            $entityManager->flush();
        }


        return $this->json($task);
    }

    #[Route('/task/{id}', name: 'task_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        // TODO: is this check necessary?
        if ($task == null) {
            return $this->redirectToRoute('task_index');
        }

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
    public function delete(EntityManagerInterface $entityManager, int $id)
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        // TODO: is this check necessary?
        if ($task != null) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index');
    }
}