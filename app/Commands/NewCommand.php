<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use DragonCode\Support\Facades\Filesystem\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use JsonException;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Enums\TypeEnum;
use PackageWizard\Installer\Fillers\AskFiller;
use PackageWizard\Installer\Fillers\DirectoryFiller;
use PackageWizard\Installer\Fillers\PackageFiller;
use PackageWizard\Installer\Fillers\Questions\AuthorFiller;
use PackageWizard\Installer\Fillers\Questions\LicenseFiller;
use PackageWizard\Installer\Helpers\ConfigHelper;
use PackageWizard\Installer\Helpers\PreviewHelper;
use PackageWizard\Installer\Services\ComposerService;
use Spatie\LaravelData\Data;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function getcwd;
use function is_readable;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\warning;
use function realpath;
use function Termwind\renderUsing;

class NewCommand extends Command
{
    protected $signature = 'new';

    protected $description = 'Create new project';

    public function __construct(
        protected ComposerService $composer
    ) {
        parent::__construct();

        renderUsing($this->output);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws JsonException
     */
    public function handle(): int
    {
        $config = $this->getConfig(
            $this->projectDirectory()
        );

        $this->questions($config);

        if (! $this->confirmChanges($config)) {
            return $this->handle();
        }

        // TODO: Replace variables

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Directory where the files should be created')
            ->addArgument('search', InputArgument::OPTIONAL, 'Package name to be installed')
            ->addOption('package-version', null, InputOption::VALUE_OPTIONAL, 'Version, will default to latest')
            ->addOption('dev', 'd', InputOption::VALUE_NONE, 'Install the latest "development" release')
            ->addOption(
                'local',
                'l',
                InputOption::VALUE_NONE,
                'Specifies that the "name" parameter specifies the path to the local project'
            )
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists');
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('');

        $output->writeln(
            <<<'TEXT'
                <fg=blue>
                  ____            _                     __        ___                  _ 
                 |  _ \ __ _  ___| | ____ _  __ _  ___  \ \      / (_)______ _ _ __ __| |
                 | |_) / _` |/ __| |/ / _` |/ _` |/ _ \  \ \ /\ / /| |_  / _` | '__/ _` |
                 |  __/ (_| | (__|   < (_| | (_| |  __/   \ V  V / | |/ / (_| | | | (_| |
                 |_|   \__,_|\___|_|\_\__,_|\__, |\___|    \_/\_/  |_/___\__,_|_|  \__,_|
                                            |___/                                        
                </>
                TEXT
        );

        $output->writeln('');

        if (! $input->getArgument('name')) {
            $input->setArgument('name', DirectoryFiller::make(local: $input->getOption('local')));
        }

        if (! $input->getArgument('search') && ! $input->getOption('local')) {
            $input->setArgument('search', PackageFiller::make());
        }
    }

    protected function getInstallationDirectory(string $name): string
    {
        return $name !== '.' ? getcwd() . '/' . $name : '.';
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function projectDirectory(): string
    {
        $directory = $this->getInstallationDirectory(
            $this->argument('name')
        );

        if (! $this->option('local')) {
            $package = $this->argument('search');
            $version = $this->option('package-version');
            $dev     = $this->option('dev');
            $ansi    = $this->option('ansi') || ! $this->option('no-ansi');

            $this->ensureDirectory($directory);

            $this->composer->createProject($directory, $package, $version, (bool) $dev, $ansi);
        }
        elseif (Directory::doesntExist($directory)) {
            warning('The directory does not exist: ' . $directory);

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }
        elseif (! is_readable($directory)) {
            warning('No access to the specified directory: ' . $directory);

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }

        return $directory;
    }

    protected function ensureDirectory(string $directory): void
    {
        if (! $this->option('force') && Directory::exists($directory)) {
            warning('Application already exists.');

            $path = realpath($directory);

            if (! confirm("Do you want to overwrite the \"$path\" directory?")) {
                warning('It\'s impossible to continue.');

                exit(static::FAILURE);
            }
        }

        Directory::ensureDelete($directory);
    }

    /**
     * @throws JsonException
     */
    protected function getConfig(string $directory): ConfigData
    {
        $this->debugMessage('Configuration loading...');

        if (! $this->option('local')) {
            $this->debugMessage('Searching for a package...');

            $package = $this->argument('search');
        }

        $this->debugMessage('Build configuration data object...');

        return ConfigHelper::data($directory, $package ?? 'default');
    }

    protected function questions(ConfigData $config): void
    {
        $this->debugMessage('We ask questions to the user ...');

        $config->questions->each(
            function (Data $item, int $index) use ($config) {
                $this->debugMessage("    Question #$index...");

                match ($item->type) {
                    TypeEnum::Ask     => $this->pushIf($config->replaces, AskFiller::make(data: $item)),
                    TypeEnum::Author  => $this->pushIf($config->authors, AuthorFiller::make(data: $item)),
                    TypeEnum::License => $this->pushIf($config->replaces, LicenseFiller::make(data: $item)),
                };
            }
        );
    }

    protected function pushIf(Collection $items, mixed $value): void
    {
        if ($value) {
            $items->push($value);
        }
    }

    protected function confirmChanges(ConfigData $config): bool
    {
        intro(PHP_EOL . 'Check the data before continuing' . PHP_EOL);

        PreviewHelper::show($config, $this->components);

        return confirm('Do you confirm generation?');
    }

    protected function debugMessage(string $message): void
    {
        $this->output->writeln($message, OutputInterface::VERBOSITY_DEBUG);
    }
}
