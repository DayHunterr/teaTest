<?php

namespace App\Command\GNews;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GNewsClient extends Command
{
    protected static $defaultName = 'app:import-gnews-articles';
    protected static $defaultDescription = 'Import articles from GNews API';
    private const BASE_URL = 'https://gnews.io/api/v4/top-headlines';

    private HttpClientInterface $client;
    private EntityManagerInterface $em;
    private string $accessKeyGNews;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, string $accessKeyGNews)
    {
        parent::__construct();
        $this->client = $client;
        $this->em = $em;
        $this->accessKeyGNews = $accessKeyGNews;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'token' => $this->accessKeyGNews,
                'lang' => 'en',
                'topic' => 'health',
                'max' => 2,
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
            $article->setAuthor($item['source']['name'] ?? 'Unknown');
            $article->setText($item['description'] ?? '');
            $article->setSmallImage($item['image'] ?? '');
            $article->setLargeImage('');

            $this->em->persist($article);
        }

        $this->em->flush();

        $output->writeln('<info>' . count($data['articles']) . ' GNews articles imported.</info>');

        return Command::SUCCESS;
    }
}
