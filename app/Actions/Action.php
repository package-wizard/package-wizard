<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use Closure;
use Illuminate\Support\Str;
use PackageWizard\Installer\Concerns\Console\HasOutput;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Services\FilesystemService;
use PackageWizard\Installer\Services\ProcessService;
use Symfony\Component\Console\Output\OutputInterface;

use function app;
use function class_basename;
use function Laravel\Prompts\spin;

abstract class Action
{
    use HasOutput;

    public const Config    = 'config';
    public const Directory = 'directory';

    protected bool $rawOutput = false;

    abstract protected function perform(): void;

    public function __construct(
        protected readonly array $parameters,
        protected readonly ProcessService $process,
        protected readonly FilesystemService $filesystem,
    ) {
        static::output(OutputInterface::VERBOSITY_DEBUG);
    }

    public static function run(array $parameters = []): void
    {
        $instance = new static(
            $parameters,
            app(ProcessService::class),
            app(FilesystemService::class),
        );

        static::verboseWriteln(static::class);

        $instance->start();
    }

    protected function start(): void
    {
        static::verbose() || $this->rawOutput
            ? $this->progress(fn () => $this->perform())
            : $this->spin(fn () => $this->perform());
    }

    protected function spin(Closure $callback, ?string $title = null): void
    {
        spin($callback, $title ?? $this->title());
    }

    protected function progress(Closure $callback, ?string $title = null): void
    {
        static::verboseWriteln($title ?? $this->title());

        $callback();
    }

    protected function steps(): int
    {
        return 1;
    }

    protected function files(): array
    {
        return $this->filesystem->allFiles(
          $this->directory()
        );
    }

    protected function parameter(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    protected function directory(): string
    {
        return $this->parameter(self::Directory) ?? $this->config()->directory;
    }

    protected function config(): ConfigData
    {
        return $this->parameter(self::Config);
    }

    protected function title(): string
    {
        $name = Str::of(static::class)
            ->classBasename()
            ->before(class_basename(self::class))
            ->lower()
            ->toString();

        return __('info.prepare', ['name' => $name]);
    }
}
