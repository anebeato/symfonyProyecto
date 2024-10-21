<?php

namespace App\Entity;

use App\Repository\UsucursoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UsucursoRepository::class)]
class Usucurso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Usucurso'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Usucurso'])]
    private ?int $nota = null;

    #[ORM\ManyToOne(inversedBy: 'usucursos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Usucurso'])]
    private ?Curso $id_curso = null;

    #[ORM\ManyToOne(inversedBy: 'usucursos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Usucurso'])]
    private ?Usuario $id_usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNota(): ?int
    {
        return $this->nota;
    }

    public function setNota(?int $nota): static
    {
        $this->nota = $nota;

        return $this;
    }

    public function getIdCurso(): ?Curso
    {
        return $this->id_curso;
    }

    public function setIdCurso(?Curso $id_curso): static
    {
        $this->id_curso = $id_curso;

        return $this;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?Usuario $id_usuario): static
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }
}