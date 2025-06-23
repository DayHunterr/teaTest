<?php

namespace App\ApiNewsClient;

use App\Entity\Article;
use App\Service\CredentialProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GNewsApiClient implements NewsClientInterface
{
    private const BASE_URL = 'https://gnews.io/api/v4/top-headlines';

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
        return 'gnews';
    }

    public function import(): int
    {
        $apiKey = $this->credentials->getKey($this->getName());
        if (!$apiKey) {
            return 0;
        }

        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'token' => $apiKey,
                'lang' => 'en',
                'topic' => 'health',
                'max' => 2,
            ],
        ]);

        $data = $response->toArray(false);

        if (empty($data['articles'] ?? [])) {
            return 0;
        }

        foreach ($data['articles'] as $item) {
            $article = new Article();
            $article->setTitle($item['title'] ?? 'No Title');
            $article->setAuthor($item['source']['name'] ?? 'Unknown');
            $article->setText($item['description'] ?? '');
            $article->setSmallImage($item['image'] ?? '');
            $article->setLargeImage('');
            $this->em->persist($article);
        }

        $this->em->flush();

        return count($data['articles']);
    }
}

