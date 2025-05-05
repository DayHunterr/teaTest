<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updated_at;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTime();
    }
}