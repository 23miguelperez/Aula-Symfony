<?php

namespace App\Entity;

use App\Repository\TareaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareaRepository::class)]
class Tarea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?bool $completada = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fechaLimite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function isCompletada(): ?bool
    {
        return $this->completada;
    }

    public function setCompletada(bool $completada): static
    {
        $this->completada = $completada;

        return $this;
    }

    public function getFechaLimite(): ?\DateTimeImmutable
    {
        return $this->fechaLimite;
    }

    public function setFechaLimite(?\DateTimeImmutable $fechaLimite): static
    {
        $this->fechaLimite = $fechaLimite;

        return $this;
    }
}
