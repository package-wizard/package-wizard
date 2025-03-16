<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Actions;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use PackageWizard\Installer\Data\ConfigData;
use PackageWizard\Installer\Services\FilesystemService;
use PackageWizard\Installer\Services\ProcessService;
use PackageWizard\Installer\Support\Console;
use Symfony\Component\Console\Output\OutputInterface;

use function app;
use function class_basename;
use function Laravel\Prompts\spin;

abstract class Action
{
    public const Config    = 'config';
    public const Directory = 'directory';

    protected OutputInterface $output;

    protected bool $rawOutput = false;

    protected ?string $prefix = 'Preparation of the';

    protected ?string $suffix = null;

    abstract protected function perform(): void;

    public function __construct(
        OutputInterface $output,
        protected readonly array $parameters = [],
        protected readonly ProcessService $process,
        protected readonly FilesystemService $filesystem,
    ) {
        $this->output = clone $output;
        $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
    }

    public static function run(OutputInterface $output, array $parameters = []): void
    {
        $instance = new static(
            $output,
            $parameters,
            app(ProcessService::class),
            app(FilesystemService::class),
        );

        $instance->verboseInfo(static::class);
        $instance->start();
    }

    protected function start(): void
    {
        $this->verbose() || $this->rawOutput
            ? $this->progress(fn () => $this->perform())
            : $this->spin(fn () => $this->perform());
    }

    protected function spin(Closure $callback, ?string $title = null): void
    {
        spin($callback, $title ?? $this->title());
    }

    protected function progress(Closure $callback, ?string $title = null): void
    {
        $this->verboseInfo($title ?? $this->title());

        $callback();
    }

    protected function steps(): int
    {
        return 1;
    }

    protected function files(): array
    {
        return $this->filesystem->allFiles(
            $this->parameter(static::Directory)
        );
    }

    protected function verboseInfo(string $message): void
    {
        if ($this->verbose()) {
            $this->output->writeln($message);
        }
    }

    protected function parameter(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    protected function directory(): string
    {
        return $this->parameter(self::Directory);
    }

    protected function config(): ConfigData
    {
        return $this->parameter(self::Config);
    }

    protected function title(): string
    {
        return Str::of(static::class)
            ->classBasename()
            ->before(class_basename(self::class))
            ->lower()
            ->when($this->prefix, static fn (Stringable $str, string $prefix) => $str->prepend($prefix, ' '))
            ->when($this->suffix, static fn (Stringable $str, string $suffix) => $str->append(' ', $suffix))
            ->append('...')
            ->ucfirst()
            ->toString();
    }

    protected function verbose(): bool
    {
        return Console::verbose();
    }
}
