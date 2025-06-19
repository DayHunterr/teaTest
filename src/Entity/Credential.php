<?php

namespace App\Entity;

use App\Repository\CredentialsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CredentialsRepository::class)
 * @ORM\Table(name="`credentials`")
 */
class Credential
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $key;

    // Геттеры и сеттеры
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setKey(?string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
}