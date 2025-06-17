<?php

namespace App\Command;

use App\Resolver\NewsCommandResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;

class ImportAllNewsCommand extends Command
{
    protected static $defaultName = 'app:import-enabled-news-sources';
    protected static $defaultDescription = 'Run all enabled news importers from the database';

    /** @var NewsCommandResolver */
    private $resolver;

    /** @var Application|null */
    private $application;

    public function __construct(NewsCommandResolver $resolver)
    {
        parent::__construct();
        $this->resolver = $resolver;
    }

    public function setApplication(Application $application = null)
    {
        parent::setApplication($application);
        $this->application = $application;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->application) {
            $this->application = $this->getApplication();
        }

        $commandNames = $this->resolver->getEnabledCommandNames();

        if (empty($commandNames)) {
            $output->writeln('<comment>No enabled news commands found.</comment>');
            return Command::SUCCESS;
        }

        foreach ($commandNames as $commandName) {
            $output->writeln("<info>Running: {$commandName}</info>");

            $command = $this->application->find($commandName);
            $returnCode = $command->run($input, $output);

            if ($returnCode !== Command::SUCCESS) {
                $output->writeln("<error>Command {$commandName} failed with code {$returnCode}</error>");
            }
        }

        return Command::SUCCESS;
    }
}
