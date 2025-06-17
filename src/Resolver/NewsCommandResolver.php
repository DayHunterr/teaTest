<?php

namespace App\Resolver;

use App\Entity\NewsApi;
use Doctrine\ORM\EntityManagerInterface;

class NewsCommandResolver
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @return string[]
     */
    public function getEnabledCommandNames(): array
    {
        $repo = $this->em->getRepository(NewsApi::class);
        $enabled = $repo->findBy(['enabled' => true]);

        $commands = [];
        foreach ($enabled as $item) {
            $commands[] = $item->getName();
        }

        return $commands;
    }
}
