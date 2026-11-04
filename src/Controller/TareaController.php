<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use App\Entity\Tarea;
use App\Form\TareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TareaController extends AbstractController
{
    #[Route('/tareas', name: 'app_tarea_index')]
    public function index(TareaRepository $tareaRepository): Response
    {
        $tareas = $tareaRepository->findRecientes();

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    #[Route('/tareas/nueva', name: 'app_tarea_nueva', methods: ['GET', 'POST'])]
    public function nueva(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tarea);
            $entityManager->flush();

            return $this->redirectToRoute('app_tarea_index');
        }

        return $this->render('tarea/nueva.html.twig', [
            'form' => $form,    
        ]);

        
    }
}