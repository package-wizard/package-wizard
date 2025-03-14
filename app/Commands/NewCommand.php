<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use PackageWizard\Installer\Fillers\DirectoryFiller;
use PackageWizard\Installer\Fillers\PackageFiller;
use PackageWizard\Installer\Services\ComposerService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use function getcwd;
use function is_dir;
use function is_readable;
use function Laravel\Prompts\warning;

class NewCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'new';

    protected $description = 'Create new project';

    public function __construct(
        protected ComposerService $composer
    ) {
        parent::__construct();
    }

    public function handle(): int
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

        // Step 2
        // TODO: Read wizard.json file and validate schema
        // TODO: Fill MainData class

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Directory where the files should be created')
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

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => fn () => DirectoryFiller::make(
                local: $this->option('local')
            ),

            'search' => fn () => PackageFiller::make(),
        ];
    }

    protected function hasAnsi(): bool
    {
        return $this->option('ansi') || ! $this->option('no-ansi');
    }

    protected function getInstallationDirectory(string $name): string
    {
        return $name !== '.' ? getcwd() . '/' . $name : '.';
    }
}
