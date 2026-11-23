<?php

namespace App\Service;

use App\Entity\Tarea;
use Doctrine\ORM\EntityManagerInterface;

class TareaManager
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function guardar(Tarea $tarea): void
    {
        $this->entityManager->persist($tarea);
        $this->entityManager->flush();
    }

    public function eliminar(Tarea $tarea): void
    {
        $this->entityManager->remove($tarea);
        $this->entityManager->flush();
    }
}
