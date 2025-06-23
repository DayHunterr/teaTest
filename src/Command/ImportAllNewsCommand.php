<?php

namespace App\Command;

use App\ApiNewsClient\Resolver\NewsClientResolver;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\NewsApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportAllNewsCommand extends Command
{
    protected static $defaultName = 'app:import-enabled-news-sources';
    protected static $defaultDescription = 'Run all enabled news API clients based on database config';

    private $em;
    private $resolver;

    public function __construct(EntityManagerInterface $em, NewsClientResolver $resolver)
    {
        parent::__construct();
        $this->em = $em;
        $this->resolver = $resolver;
        $this->logFile = dirname(__DIR__, 2) . '/var/log/import_news_api.log';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $apis = $this->em->getRepository(NewsApi::class)->findBy(['enabled' => true]);

            if (empty($apis)) {
                $this->log('No enabled API clients found.', $output, 'comment');
                return Command::SUCCESS;
            }

            foreach ($apis as $api) {
                $client = $this->resolver->resolve($api->getName());

                if (!$client) {
                    $this->log("No client found for: {$api->getName()}", $output, 'error');
                    continue;
                }

                $count = $client->import();
                $this->log("Imported {$count} articles using '{$api->getName()}' client.", $output, 'info');
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->log("Error occurred: {$e->getMessage()}", $output, 'error');
            $this->log($e->getTraceAsString(), $output);
            return Command::FAILURE;
        }
    }

    private function log(string $message, OutputInterface $output, string $type = 'info'): void
    {
        // В консоль
        switch ($type) {
            case 'error':
                $output->writeln("<error>{$message}</error>");
                break;
            case 'comment':
                $output->writeln("<comment>{$message}</comment>");
                break;
            case 'info':
            default:
                $output->writeln("<info>{$message}</info>");
                break;
        }

        $timestamp = (new \DateTime())->format('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[{$timestamp}] {$message}\n", FILE_APPEND);
    }
}
