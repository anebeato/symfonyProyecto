<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $admin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foto = null;

    /**
     * @var Collection<int, Usucurso>
     */
    #[ORM\OneToMany(targetEntity: Usucurso::class, mappedBy: 'id_usuario')]
    private Collection $usucursos;

    public function __construct()
    {
        $this->usucursos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): static
    {
        $this->foto = $foto;

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
            $usucurso->setIdUsuario($this);
        }

        return $this;
    }

    public function removeUsucurso(Usucurso $usucurso): static
    {
        if ($this->usucursos->removeElement($usucurso)) {
            // set the owning side to null (unless already changed)
            if ($usucurso->getIdUsuario() === $this) {
                $usucurso->setIdUsuario(null);
            }
        }

        return $this;
    }
}
