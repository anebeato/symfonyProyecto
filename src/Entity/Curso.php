<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Usucurso>
     */
    #[ORM\OneToMany(targetEntity: Usucurso::class, mappedBy: 'id_curso')]
    private Collection $usucursos;

    public function __construct()
    {
        $this->usucursos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Usucurso>
     */
    public function getUsucursos(): Collection
    {
        return $this->usucursos;
    }

    public function addUsucurso(Usucurso $usucurso): static
    {
        if (!$this->usucursos->contains($usucurso)) {
            $this->usucursos->add($usucurso);
            $usucurso->setIdCurso($this);
        }

        return $this;
    }

    public function removeUsucurso(Usucurso $usucurso): static
    {
        if ($this->usucursos->removeElement($usucurso)) {
            // set the owning side to null (unless already changed)
            if ($usucurso->getIdCurso() === $this) {
                $usucurso->setIdCurso(null);
            }
        }

        return $this;
    }
}
