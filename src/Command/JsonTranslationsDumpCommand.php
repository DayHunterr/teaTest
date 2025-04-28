<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

final class JsonTranslationsDumpCommand extends Command
{
    protected static $defaultName = 'json_translations:dump';
    protected static $defaultDescription = 'Dump all translations from the translation folder into a single file.';

    /**
     * @var string
     */
    private string $translationsDir;

    /**
     * @var string
     */
    private string $outputDir;

    /**
     * @var string
     */
    private string $locale;

    /**
     * @param string $translationsDir
     * @param string $outputDir
     * @param string $locale
     */
    public function __construct(string $translationsDir, string $outputDir, string $locale)
    {
        parent::__construct();

        $this->translationsDir = $translationsDir;
        $this->outputDir = $outputDir;
        $this->locale = $locale;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $translations = [];
        $io = new SymfonyStyle($input, $output);

        foreach (glob($this->translationsDir . '/*.yaml') as $file) {
            $locale = $this->extractLocale($file);

            if(strtolower($locale) !== strtolower($this->locale)) {
                continue;
            }

            $translations[$locale][$this->extractDomain($file)] = Yaml::parseFile($file);
        }

        file_put_contents($this->outputDir, json_encode($translations, JSON_PRETTY_PRINT));

        $io->success("Translations have been dumped to $this->outputDir");

        return Command::SUCCESS;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function extractLocale(string $file): string
    {
        preg_match('/\.(.+)\.yaml/', $file, $m);

        return $m[1];
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function extractDomain(string $file): string
    {
        $domainPath = preg_replace('/\.(.+)\.yaml/', '', $file);

        return str_replace($this->translationsDir.'/', '', $domainPath);
    }
}