<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Illuminate\Console\Command;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Fillers\DirectoryFiller;
use PackageWizard\Installer\Fillers\PackageFiller;
use PackageWizard\Installer\Helpers\ConfigHelper;
use PackageWizard\Installer\Services\ComposerService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function getcwd;
use function is_dir;
use function is_readable;
use function Laravel\Prompts\warning;

class NewCommand extends Command
{
    protected $signature = 'new';

    protected $description = 'Create new project';

    public function __construct(
        protected ComposerService $composer
    ) {
        parent::__construct();
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle(): int
    {
        $config = $this->getConfig(
            $directory = $this->projectDirectory()
        );

        dd(
            $config
        );

        // Step 2
        // TODO: Read wizard.json file and validate schema
        // TODO: Fill MainData class

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Directory where the files should be created')
            ->addArgument('search', InputArgument::OPTIONAL, 'Package name to be installed')
            ->addOption('package-version', null, InputOption::VALUE_OPTIONAL, 'Version, will default to latest')
            ->addOption('dev', null, InputOption::VALUE_NONE, 'Install the latest "development" release')
            ->addOption(
                'local',
                null,
                InputOption::VALUE_NONE,
                'Specifies that the "name" parameter specifies the path to the local project'
            );
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
            $input->setArgument(
                'name',
                DirectoryFiller::make(local: $input->getOption('local'))
            );
        }

        if (! $input->getArgument('search') && ! $input->getOption('local')) {
            $input->setArgument('search', PackageFiller::make());
        }
    }

    protected function hasAnsi(): bool
    {
        return $this->option('ansi') || ! $this->option('no-ansi');
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
            $ansi    = $this->hasAnsi();

            $this->composer->createProject($directory, $package, $version, (bool) $dev, $ansi);
        }
        elseif (! is_dir($directory)) {
            warning('The directory does not exist: ' . $directory);

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }
        elseif (! is_readable($directory)) {
            warning('No access to the specified directory');

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }

        return $directory;
    }

    protected function getConfig(string $directory): ConfigData
    {
        return ConfigHelper::data($directory);
    }
}
