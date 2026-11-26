<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Entity\User;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use App\Service\TareaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class TareaController extends AbstractController
{
    #[Route('/tareas', name: 'app_tarea_index')]
    public function index(Request $request, TareaRepository $tareaRepository, #[CurrentUser] User $usuario): Response
    {
        $tareas = match ($request->query->getString('estado')) {
            'pendiente' => $tareaRepository->findPendientes($usuario),
            'completada' => $tareaRepository->findCompletadas($usuario),
            default => $tareaRepository->findRecientes($usuario),
        };

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    #[Route('/tareas/nueva', name: 'app_tarea_nueva', methods: ['GET', 'POST'])]
    public function nueva(Request $request, TareaManager $tareaManager, #[CurrentUser] User $usuario): Response
    {
        $tarea = new Tarea();
        $tarea->setPropietario($usuario);

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
    #[IsGranted('TAREA_VER', subject: 'tarea')]
    public function ver(Tarea $tarea): Response
    {
        return $this->render('tarea/ver.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route('/tareas/{id}/editar', name: 'app_tarea_editar', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('TAREA_EDITAR', subject: 'tarea')]
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
    #[IsGranted('TAREA_ELIMINAR', subject: 'tarea')]
    public function eliminar(Request $request, Tarea $tarea, TareaManager $tareaManager): Response
    {
        if ($this->isCsrfTokenValid('eliminar'.$tarea->getId(), $request->request->getString('_token'))) {
            $tareaManager->eliminar($tarea);
            $this->addFlash('success', 'Tarea eliminada.');
        }

        return $this->redirectToRoute('app_tarea_index');
    }
}