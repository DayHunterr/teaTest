<?php

namespace App\Resolver;

use App\ApiClient\NewsClientInterface;

class NewsClientResolver
{
    /** @var NewsClientInterface[] */
    private array $clients;

    /**
     * @param NewsClientInterface[] $clients
     */
    public function __construct(array $clients)
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