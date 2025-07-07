<?php

namespace App\Controller\Admin\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    private TagRepository $tagRepository;

    public function __construct(
        EntityManagerInterface $em,
        TagRepository $tagRepository
    )
    {
        $this->tagRepository = $tagRepository;
    }

    public function transform($tags): string
    {
        if (null === $tags || $tags->isEmpty()) {
            return '';
        }

        return implode(', ', $tags->map(fn(Tag $tag) => $tag->getName())->toArray());
    }

    public function reverseTransform($value): ?Collection
    {
        if (!$value) {
            return null;
        }
        $items = explode(',', $value);
        $items = array_map('trim', $items);
        $items = array_unique($items);

        $tags = new ArrayCollection();

        foreach ($items as $item) {
            $tag = ((new Tag())->setName($item));
            $tags->add($tag);
        }

        return $tags;
    }
}