<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{

    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $smallImage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $largeImage;

    /**
     * @return mixed
     */
    public function getSmallImage()
    {
        return $this->smallImage;
    }

    /**
     * @param mixed $smallImage
     */
    public function setSmallImage($smallImage): void
    {
        $this->smallImage = $smallImage;
    }

    /**
     * @return mixed
     */
    public function getLargeImage()
    {
        return $this->largeImage;
    }

    /**
     * @param mixed $largeImage
     */
    public function setLargeImage($largeImage): void
    {
        $this->largeImage = $largeImage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
