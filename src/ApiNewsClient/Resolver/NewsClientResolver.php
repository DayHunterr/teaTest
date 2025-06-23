<?php

namespace App\ApiNewsClient\Resolver;

use App\ApiNewsClient\NewsClientInterface;

class NewsClientResolver
{
    /** @var NewsClientInterface[] */
    private iterable $clients;

    /**
     * @param iterable<NewsClientInterface[]> $clients
     */
    public function __construct(iterable $clients)
    {
        $this->clients = $clients;
    }

    public function resolve(string $name): ?NewsClientInterface
    {
        foreach ($this->clients as $client) {
            if ($client->getName() === $name) {
                return $client;
            }
        }

        return null;
    }
}