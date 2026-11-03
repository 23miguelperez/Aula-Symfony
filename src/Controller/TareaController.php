<?php

namespace App\Controller;

use App\Repository\TareaRepository;
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
}