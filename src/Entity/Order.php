<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^[^\d]+$/u",
     *      message="Full name cannot contain numbers."
     *  )
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(mode="html5")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *      pattern="/^\+\d{7,15}$/",
     *      message="Phone must start with '+' and contain only digits."
     *  )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tea_type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^\d+$/",
     *      message="Quantity must be a number."
     *  )
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTeaType(): ?string
    {
        return $this->tea_type;
    }

    public function setTeaType(string $tea_type): self
    {
        $this->tea_type = $tea_type;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
