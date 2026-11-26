<?php

namespace App\Repository;

use App\Entity\Tarea;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tarea>
 */
class TareaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarea::class);
    }

    /**
     * @return Tarea[]
     */
    public function findRecientes(User $propietario): array
    {
        return $this->findBy(['propietario' => $propietario], ['id' => 'DESC']);
    }

    /**
     * @return Tarea[]
     */
    public function findPendientes(User $propietario): array
    {
        return $this->findBy(['propietario' => $propietario, 'completada' => false], ['id' => 'DESC']);
    }

    /**
     * @return Tarea[]
     */
    public function findCompletadas(User $propietario): array
    {
        return $this->findBy(['propietario' => $propietario, 'completada' => true], ['id' => 'DESC']);
    }
}