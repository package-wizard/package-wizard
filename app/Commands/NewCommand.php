<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use DragonCode\Support\Facades\Filesystem\Directory;
use Illuminate\Console\Command;
use JsonException;
use PackageWizard\Installer\Actions\Action;
use PackageWizard\Installer\Actions\AuthorsAction;
use PackageWizard\Installer\Actions\CleanUpAction;
use PackageWizard\Installer\Actions\CopyFilesAction;
use PackageWizard\Installer\Actions\Dependencies\InstallDependenciesAction;
use PackageWizard\Installer\Actions\Dependencies\SyncDependenciesAction;
use PackageWizard\Installer\Actions\DownloadProjectAction;
use PackageWizard\Installer\Actions\QuestionsAction;
use PackageWizard\Installer\Actions\RemoveFilesAction;
use PackageWizard\Installer\Actions\RenameFilesAction;
use PackageWizard\Installer\Actions\ReplaceContentAction;
use PackageWizard\Installer\Actions\VariablesAction;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Enums\DependencyTypeEnum;
use PackageWizard\Installer\Fillers\DirectoryFiller;
use PackageWizard\Installer\Fillers\PackageFiller;
use PackageWizard\Installer\Helpers\ConfigHelper;
use PackageWizard\Installer\Helpers\PreviewHelper;
use PackageWizard\Installer\Support\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function file_get_contents;
use function getcwd;
use function is_readable;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;
use function PackageWizard\Installer\resource_path;
use function realpath;
use function Termwind\renderUsing;

// TODO: Add schema validator
// TODO: Add License file copying
// TODO: Add license file link replace
// TODO: Fix field titles
// TODO: Make EqualsTo as default for comparing
// TODO: Add forced boilerplates list
// TODO: Rename `comparator` with `operator`, simplify and add existsPath and doesntExistPath to options
// TODO: Rename `wizard.install.*` with `wizard.managers.*`
// TODO: Extract questions to language files
class NewCommand extends Command
{
    protected $signature = 'new';

    protected $description = 'Create new project';

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws JsonException
     */
    public function handle(?string $directory = null): int
    {
        $config = $this->getConfig(
            $directory ??= $this->projectDirectory()
        );

        AuthorsAction::run($this->getOutput(), [
            Action::Config => $config,
        ]);

        VariablesAction::run($this->getOutput(), [
            Action::Config => $config,
        ]);

        QuestionsAction::run($this->getOutput(), [
            Action::Config => $config,
        ]);

        if (! $this->confirmChanges($config)) {
            return $this->handle($directory);
        }

        ReplaceContentAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        RenameFilesAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        RemoveFilesAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        CopyFilesAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        SyncDependenciesAction::run($this->getOutput(), [
            SyncDependenciesAction::Type => DependencyTypeEnum::Composer,
            Action::Directory            => $directory,
            Action::Config               => $config,
        ]);

        SyncDependenciesAction::run($this->getOutput(), [
            SyncDependenciesAction::Type => DependencyTypeEnum::Npm,
            Action::Directory            => $directory,
            Action::Config               => $config,
        ]);

        SyncDependenciesAction::run($this->getOutput(), [
            SyncDependenciesAction::Type => DependencyTypeEnum::Yarn,
            Action::Directory            => $directory,
            Action::Config               => $config,
        ]);

        InstallDependenciesAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        CleanUpAction::run($this->getOutput(), [
            Action::Directory => $directory,
            Action::Config    => $config,
        ]);

        $this->output->writeln('');
        $this->components->success('  Congratulations! <options=bold>Build something amazing!</>');
        $this->output->writeln('');

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
        renderUsing($this->output);

        Console::setAnsi(
            enabled: $this->option('ansi') || ! $this->option('no-ansi')
        );

        Console::setVerbose(
            enabled: $this->option('verbose')
        );

        $this->newLine();
        $output->writeln(file_get_contents(resource_path('stubs/logotype.stub')));
        $this->newLine();

        if (! $input->getArgument('name')) {
            $input->setArgument('name', DirectoryFiller::make(local: $input->getOption('local')));
        }

        if (! $input->getArgument('search') && ! $input->getOption('local')) {
            $input->setArgument('search', PackageFiller::make());
        }
    }

    protected function getInstallationDirectory(string $name): string
    {
        if ($path = realpath($name)) {
            return $path;
        }

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
            $this->ensureDirectory($directory);

            DownloadProjectAction::run($this->getOutput(), [
                DownloadProjectAction::Package => $this->argument('search'),
                DownloadProjectAction::Version => $this->option('package-version'),
                DownloadProjectAction::Dev     => (bool) $this->option('dev'),
                Action::Directory              => $directory,
            ]);
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
        if (! $this->option('local')) {
            $this->debugMessage('Searching for a package...');

            $package = $this->argument('search');
        }

        return spin(
            callback: fn () => ConfigHelper::data($directory, $package ?? 'default'),
            message : 'Build configuration...'
        );
    }

    protected function confirmChanges(ConfigData $config): bool
    {
        intro(PHP_EOL . 'Check the data before continuing' . PHP_EOL);

        $doesntAsk = $config->replaces->where('asked', true)->isEmpty();

        if ($doesntAsk) {
            return true;
        }

        PreviewHelper::replaces($config->replaces);

        $this->newLine();

        return confirm('Do you confirm generation?');
    }

    protected function debugMessage(string $message): void
    {
        if (Console::verbose()) {
            $this->output->writeln($message, OutputInterface::VERBOSITY_DEBUG);
        }
    }
}
