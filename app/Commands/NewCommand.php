<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Commands;

use DragonCode\Support\Facades\Filesystem\Directory;
use Illuminate\Console\Command;
use JsonException;
use LaravelLang\Locales\Facades\Locales;
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
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function __;
use function file_get_contents;
use function getcwd;
use function is_readable;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;
use function PackageWizard\Installer\resource_path;
use function realpath;
use function Termwind\renderUsing;

// TODO: Add schema validator
// TODO: Add License file copying
// TODO: Add license file link replace
// TODO: Add forced boilerplates list
#[AsCommand('new', 'Create new project')]
class NewCommand extends Command
{
    protected $signature = 'new';

    protected $description = 'Create new project';

    protected ?string $directory = null;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws JsonException
     */
    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            error(__('validation.interactive'));

            return static::FAILURE;
        }

        $config = $this->getConfig(
            $this->directory ??= $this->projectDirectory()
        );

        AuthorsAction::run([Action::Config => $config]);
        VariablesAction::run([Action::Config => $config]);
        QuestionsAction::run([Action::Config => $config]);

        if (! $this->confirmChanges($config)) {
            return $this->handle();
        }

        ReplaceContentAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        RenameFilesAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        RemoveFilesAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        CopyFilesAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        SyncDependenciesAction::run([
            SyncDependenciesAction::Type => DependencyTypeEnum::Composer,
            Action::Directory            => $this->directory,
            Action::Config               => $config,
        ]);

        SyncDependenciesAction::run([
            SyncDependenciesAction::Type => DependencyTypeEnum::Npm,
            Action::Directory            => $this->directory,
            Action::Config               => $config,
        ]);

        SyncDependenciesAction::run([
            SyncDependenciesAction::Type => DependencyTypeEnum::Yarn,
            Action::Directory            => $this->directory,
            Action::Config               => $config,
        ]);

        InstallDependenciesAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        CleanUpAction::run([
            Action::Directory => $this->directory,
            Action::Config    => $config,
        ]);

        $this->output->writeln('');
        $this->components->success(__('info.congratulations'));
        $this->output->writeln('');

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('template', InputArgument::OPTIONAL, 'Template name to be installed')
            ->addArgument('name', InputArgument::OPTIONAL, 'Directory where the files should be created')
            ->addOption('package-version', null, InputOption::VALUE_OPTIONAL, 'Version, will default to latest')
            ->addOption('dev', 'd', InputOption::VALUE_NONE, 'Install the development version')
            ->addOption('local', 'l', InputOption::VALUE_NONE, 'Uses a template from the local folder')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists')
            ->addOption('lang', null, InputOption::VALUE_OPTIONAL, 'In which language to display messages');
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws JsonException
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

        if ($locale = $input->getOption('lang')) {
            Locales::set($locale);
        }

        if (! $input->getArgument('template') && ! $input->getOption('local')) {
            $input->setArgument('template', PackageFiller::make());
        }

        if (! $input->getArgument('name')) {
            $input->setArgument('name', DirectoryFiller::make(local: $input->getOption('local')));
        }
    }

    protected function getInstallationDirectory(string $name): string
    {
        if ($path = realpath($name)) {
            return $path;
        }

        return $name !== '.' ? getcwd() . '/' . $name : '.';
    }

    protected function projectDirectory(): string
    {
        $directory = $this->getInstallationDirectory(
            $this->argument('name')
        );

        if (! $this->option('local')) {
            $this->ensureDirectory($directory);

            DownloadProjectAction::run([
                DownloadProjectAction::Package => $this->argument('template'),
                DownloadProjectAction::Version => $this->option('package-version'),
                DownloadProjectAction::Dev     => (bool) $this->option('dev'),
                Action::Directory              => $directory,
            ]);
        }
        elseif (Directory::doesntExist($directory)) {
            warning(__('validation.doesnt_exist.directory', ['path' => $directory]));

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }
        elseif (! is_readable($directory)) {
            warning(__('validation.no_access', ['path' => $directory]));

            $directory = $this->getInstallationDirectory(
                DirectoryFiller::make(local: true)
            );
        }

        return $directory;
    }

    protected function ensureDirectory(string $directory): void
    {
        if (! $this->option('force') && Directory::exists($directory)) {
            warning(__('validation.exists.app'));

            $path = realpath($directory);

            if (! confirm(__('info.overwrite', ['path' => $path]))) {
                warning(__('info.impossible'));

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
            $this->debugMessage(__('info.searching'));

            $package = $this->argument('template');
        }

        return spin(
            callback: fn () => ConfigHelper::data($directory, $package ?? 'default'),
            message : __('info.build_config')
        );
    }

    protected function confirmChanges(ConfigData $config): bool
    {
        intro(PHP_EOL . __('info.check_data') . PHP_EOL);

        $doesntAsk = $config->replaces->where('asked', true)->isEmpty();

        if ($doesntAsk) {
            return true;
        }

        PreviewHelper::replaces($config->replaces);

        $this->newLine();

        return confirm(__('info.accept'));
    }

    protected function debugMessage(string $message): void
    {
        if (Console::verbose()) {
            $this->output->writeln($message, OutputInterface::VERBOSITY_DEBUG);
        }
    }
}
