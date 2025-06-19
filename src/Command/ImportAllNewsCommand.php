<?php

namespace App\Command;

use App\Resolver\NewsClientResolver;
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
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(NewsApi::class);
        $apis = $repo->findBy(['enabled' => true]);

        if (empty($apis)) {
            $output->writeln('<comment>No enabled API clients found.</comment>');
            return Command::SUCCESS;
        }

        foreach ($apis as $api) {
            $client = $this->resolver->resolve($api->getName());

            if (!$client) {
                $output->writeln("<error>No client found for: {$api->getName()}</error>");
                continue;
            }

            $count = $client->import();
            $output->writeln("<info>Imported {$count} articles using '{$api->getName()}' client.</info>");
        }

        return Command::SUCCESS;
    }
}
