<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @var Collection<int, Usuario>
     */
    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'id_curso_usuario')]
    private Collection $nota;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $nota = null;

    public function __construct()
    {
        $this->nota = new ArrayCollection();
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
     * @return Collection<int, Usuario>
     */
    public function getNota(): Collection
    {
        return $this->nota;
    }

    public function addNotum(Usuario $notum, ?float $nota = null): static
    {
        if (!$this->nota->contains($notum)) {
            $this->nota->add($notum);
            $notum->addIdCursoUsuario($this, $nota);
        }

        return $this;
    }

    public function removeNotum(Usuario $notum): static
    {
        if ($this->nota->removeElement($notum)) {
            $notum->removeIdCursoUsuario($this);
        }

        return $this;
    }

    public function getNotaValue(): ?float
    {
        return $this->nota;
    }

    public function setNotaValue(?float $nota): static
    {
        $this->nota = $nota;

        return $this;
    }
}
