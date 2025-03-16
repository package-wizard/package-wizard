<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use Closure;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JsonException;
use PackageWizard\Installer\Data\AuthorData;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Enums\RenameEnum;
use PackageWizard\Installer\Enums\TypeEnum;
use PackageWizard\Installer\Fillers\AskFiller;
use PackageWizard\Installer\Fillers\DirectoryFiller;
use PackageWizard\Installer\Fillers\PackageFiller;
use PackageWizard\Installer\Fillers\Questions\AuthorFiller;
use PackageWizard\Installer\Fillers\Questions\LicenseFiller;
use PackageWizard\Installer\Helpers\ConfigHelper;
use PackageWizard\Installer\Helpers\PreviewHelper;
use PackageWizard\Installer\Replacers\AskReplacer;
use PackageWizard\Installer\Replacers\AuthorReplacer;
use PackageWizard\Installer\Replacers\LicenseReplacer;
use PackageWizard\Installer\Replacers\VariableReplacer;
use PackageWizard\Installer\Services\ComposerService;
use PackageWizard\Installer\Services\FilesystemService;
use PackageWizard\Installer\Services\NpmService;
use PackageWizard\Installer\Services\ReplaceService;
use PackageWizard\Installer\Services\YarnService;
use Spatie\LaravelData\Data;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function config;
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
        protected ComposerService $composer,
        protected NpmService $npm,
        protected YarnService $yarn,
    ) {
        parent::__construct();

        renderUsing($this->output);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws JsonException
     */
    public function handle(FilesystemService $filesystem, ReplaceService $replacer): int
    {
        $config = $this->getConfig(
            $directory = $this->projectDirectory()
        );

        $this->authors($config);
        $this->variables($config);
        $this->questions($config);

        if (! $this->confirmChanges($config)) {
            return $this->handle($filesystem, $replacer);
        }

        $this->newLine();

        info('Replace...');

        $this->withProgressBar(
            $filesystem->allFiles($directory),
            static fn (string $path) => $replacer->replace($path, $config->replaces)
        );

        $this->newLine();

        if ($config->renames->isNotEmpty()) {
            info('Rename...');

            $this->withProgressBar(
                $filesystem->allFiles($directory),
                static function (string $path) use ($filesystem, $directory, $config) {
                    $basename = Str::of(realpath($path))
                        ->after(realpath($directory))
                        ->replace('\\', '/')
                        ->ltrim('/')
                        ->toString();

                    foreach ($config->renames as $rename) {
                        if ($rename->what === RenameEnum::Path && $basename === $rename->source) {
                            $basename = $rename->target;
                        }

                        if ($rename->what === RenameEnum::Name) {
                            $basename = Str::of($basename)
                                ->explode('/')
                                ->map(static fn (string $name) => $name === $rename->source ? $rename->target : $name)
                                ->join('/');
                        }
                    }

                    $filesystem->rename($path, $directory . '/' . $basename);
                }
            );

            $this->newLine();
        }

        if ($config->removes->isNotEmpty()) {
            info('Remove...');

            $this->withProgressBar(
                $config->removes,
                static fn (string $path) => $filesystem->remove($path)
            );

            $this->newLine();
        }

        $this->newLine();

        $this->installDependencies($config, $directory);
        $this->cleanUp($config, $directory);

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

            $this->ensureDirectory($directory);

            $this->composer->createProject($directory, $package, $version, (bool) $dev, $this->hasAnsi());
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

    protected function authors(ConfigData $config): void
    {
        $this->debugMessage('Processing of authors...');

        $config->authors->each(function (AuthorData $item, int $index) use ($config) {
            $this->debugMessage("    Author #$index...");

            $author = AuthorReplacer::get($item);

            $config->replaces->push($author);
        });
    }

    protected function variables(ConfigData $config): void
    {
        $this->debugMessage('Processing of variables...');

        $config->variables->each(function (Data $item, int $index) use ($config) {
            $this->debugMessage("    Variable #$index...");

            $variable = VariableReplacer::get($item);

            $config->replaces->push($variable);
        });
    }

    protected function questions(ConfigData $config): void
    {
        $this->debugMessage('We ask questions to the user...');

        $config->questions->each(
            function (Data $item, int $index) use ($config) {
                $this->debugMessage("    Question #$index...");

                $value = match ($item->type) {
                    TypeEnum::Ask     => AskReplacer::get(AskFiller::make(data: $item), true),
                    TypeEnum::Author  => AuthorReplacer::get(AuthorFiller::make(data: $item), true),
                    TypeEnum::License => LicenseReplacer::get(LicenseFiller::make(data: $item), true),
                };

                if ($value) {
                    $config->replaces->push($value);
                }
            }
        );
    }

    protected function confirmChanges(ConfigData $config): bool
    {
        intro(PHP_EOL . 'Check the data before continuing' . PHP_EOL);

        PreviewHelper::replaces($config->replaces);

        $this->newLine();

        return confirm('Do you confirm generation?');
    }

    protected function installDependencies(ConfigData $config, string $directory): void
    {
        $this->installDependency(
            when   : $config->wizard->install->composer,
            command: fn () => $this->composer->update($directory, $this->hasAnsi()),
            what   : 'composer'
        );

        $this->installDependency(
            when   : $config->wizard->install->npm,
            command: fn () => $this->npm->install($directory),
            what   : 'npm'
        );
        $this->installDependency(
            when   : $config->wizard->install->yarn,
            command: fn () => $this->yarn->install($directory),
            what   : 'yarn'
        );
    }

    protected function cleanUp(ConfigData $config, string $directory): void
    {
        if (! $config->wizard->clean) {
            $this->debugMessage('Clean up is disabled.');

            return;
        }

        $this->debugMessage('Removing wizard.json file from the project');

        File::ensureDelete($directory . '/' . config('wizard.filename'));
    }

    protected function installDependency(bool $when, Closure $command, string $what): void
    {
        if ($when) {
            info('Install ' . $what . ' dependencies...');

            $command();
        }
        else {
            $this->debugMessage(Str::ucfirst($what) . ' dependencies installation skipped.');
        }
    }

    protected function debugMessage(string $message): void
    {
        $this->output->writeln($message, OutputInterface::VERBOSITY_DEBUG);
    }

    protected function hasAnsi(): bool
    {
        return $this->option('ansi') || ! $this->option('no-ansi');
    }
}
