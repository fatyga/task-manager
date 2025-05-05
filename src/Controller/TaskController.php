<?php
namespace App\Controller;

use App\Form\Type\TaskType;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class TaskController extends AbstractController
{
    #[Route('/task/index')]
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
        }
        return $this->render('task/new.html.twig', [
            'form' => $form
        ]);
    }
}