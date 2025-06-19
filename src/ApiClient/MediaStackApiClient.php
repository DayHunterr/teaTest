<?php

namespace App\ApiClient;

use App\Entity\Article;
use App\Service\CredentialProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MediaStackApiClient implements NewsClientInterface
{
    private const BASE_URL = 'http://api.mediastack.com/v1/news';

    private $client;
    private $em;
    private $credentials;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, CredentialProvider $credentials)
    {
        $this->client = $client;
        $this->em = $em;
        $this->credentials = $credentials;
    }

    public function getName(): string
    {
        return 'mediastack';
    }

    public function import(): int
    {
        $apiKey = $this->credentials->getKey($this->getName());
        if (!$apiKey) {
            return 0;
        }

        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'access_key' => $apiKey,
                'languages' => 'en',
                'limit' => 2,
                'categories' => 'health',
            ],
        ]);

        $data = $response->toArray(false);

        if (empty($data['data'] ?? [])) {
            return 0;
        }

        foreach ($data['data'] as $item) {
            $article = new Article();
            $article->setTitle($item['title'] ?? 'No Title');
            $article->setAuthor($item['author'] ?? 'Unknown');
            $article->setText($item['description'] ?? '');
            $article->setSmallImage($item['image'] ?? '');
            $article->setLargeImage('');
            $this->em->persist($article);
        }

        $this->em->flush();

        return count($data['data']);
    }
}