<?php

namespace App\Command\MediaStack;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


final class MediaStackClient extends Command
{
    protected static $defaultName = 'app:import-articles';
    protected static $defaultDescription = 'Import articles from MediaStack API';
    private const BASE_URL = 'http://api.mediastack.com/v1/news';

    private HttpClientInterface $client;
    private EntityManagerInterface $em;
    private string $accessKeyMediaStack;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em, string $accessKeyMediaStack)
    {
        parent::__construct();
        $this->client = $client;
        $this->em = $em;
        $this->accessKeyMediaStack = $accessKeyMediaStack;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', self::BASE_URL, [
            'query' => [
                'access_key' => $this->accessKeyMediaStack,
                'languages' => 'en',
                'limit' => 2,
                'categories' => 'health',
            ],
        ]);

        $data = $response->toArray(false);

        if (!isset($data['data'])) {
            $output->writeln('<error>No data for import</error>');
            return Command::FAILURE;
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

        $output->writeln('<info>' . count($data['data']) . ' articles imported.</info>');

        return Command::SUCCESS;
    }
}
