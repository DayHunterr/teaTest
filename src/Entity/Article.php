<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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
     * @ORM\Column(type="string", length=1000)
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
     * @ManyToMany(targetEntity="App\Entity\Tag",cascade={"persist"})
     * @JoinTable(
     *     name="tags_to_articles",
     *     joinColumns={
     *         @JoinColumn(name="article_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @JoinColumn(name="tag_id", referencedColumnName="id", unique=true)
     *     }
     * )
     */
    private Collection $tags;

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

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }
}
