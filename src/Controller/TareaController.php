<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use App\Entity\Tarea;
use App\Form\TareaType;
use App\Service\TareaManager;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    #[IsGranted('ROLE_USER')]
    public function nueva(Request $request, TareaManager $tareaManager): Response
    {
        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tareaManager->guardar($tarea);

            $this->addFlash('success', 'Tarea creada.');
            return $this->redirectToRoute('app_tarea_index');
        }

        return $this->render('tarea/nueva.html.twig', [
            'form' => $form,    
        ]);
    }

    #[Route('/tareas/{id}', name: 'app_tarea_ver', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function ver(Tarea $tarea): Response
    {
        return $this->render('tarea/ver.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    
    #[Route('/tareas/{id}/editar', name: 'app_tarea_editar', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function editar(Request $request, Tarea $tarea, TareaManager $tareaManager): Response
    {
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tareaManager->guardar($tarea);

            $this->addFlash('success', 'Tarea actualizada.');
            return $this->redirectToRoute('app_tarea_ver', ['id' => $tarea->getId()]);
        }

        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
            'form' => $form,
        ]);
    }

    #[Route('/tareas/{id}/eliminar', name: 'app_tarea_eliminar', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function eliminar(Request $request, Tarea $tarea, TareaManager $tareaManager): Response
    {
        if ($this->isCsrfTokenValid('eliminar'.$tarea->getId(), $request->request->getString('_token'))) {
            $tareaManager->eliminar($tarea);
        }

        return $this->redirectToRoute('app_tarea_index');
    }
}