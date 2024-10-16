<?php
// src/Entity/Usuario.php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $admin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foto = null;

    /**
     * @var Collection<int, Curso>
     */
    #[ORM\ManyToMany(targetEntity: Curso::class, inversedBy: 'nota')]
    private Collection $id_curso_usuario;

    public function __construct()
    {
        $this->id_curso_usuario = new ArrayCollection();
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
     * @return Collection<int, Curso>
     */
    public function getIdCursoUsuario(): Collection
    {
        return $this->id_curso_usuario;
    }

    public function addIdCursoUsuario(Curso $idCursoUsuario): static
    {
        if (!$this->id_curso_usuario->contains($idCursoUsuario)) {
            $this->id_curso_usuario->add($idCursoUsuario);
        }

        return $this;
    }

    public function removeIdCursoUsuario(Curso $idCursoUsuario): static
    {
        $this->id_curso_usuario->removeElement($idCursoUsuario);

        return $this;
    }

    // Métodos requeridos por UserInterface
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // Si tienes datos temporales sensibles en el usuario, bórralos aquí
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}