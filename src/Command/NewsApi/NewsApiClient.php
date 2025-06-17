<?php

namespace App\Command\NewsApi;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsApiClient extends Command
{
    protected static $defaultName = 'app:import-newsapi-articles';
    protected static $defaultDescription = 'Import articles from NewsAPI.org';
    private const BASE_URL = 'https://newsapi.org/v2/top-headlines';

    private HttpClientInterface $client;
    private EntityManagerInterface $em;
    private string $accessNewsApi;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, string $accessNewsApi)
    {
        parent::__construct();
        $this->client = $client;
        $this->em = $em;
        $this->accessNewsApi = $accessNewsApi;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'apiKey' => $this->accessNewsApi,
                'language' => 'en',
                'category' => 'health',
                'pageSize' => 2,
            ],
        ]);

        $data = $response->toArray(false);

        if (!isset($data['articles']) || empty($data['articles'])) {
            $output->writeln('<error>No articles found</error>');
            return Command::FAILURE;
        }

        foreach ($data['articles'] as $item) {
            $article = new Article();
            $article->setTitle($item['title'] ?? 'No Title');
            $article->setAuthor($item['author'] ?? 'Unknown');
            $article->setText($item['description'] ?? '');
            $article->setSmallImage($item['urlToImage'] ?? '');
            $article->setLargeImage('');

            $this->em->persist($article);
        }

        $this->em->flush();

        $output->writeln('<info>' . count($data['articles']) . ' NewsAPI articles imported.</info>');

        return Command::SUCCESS;
    }
}
