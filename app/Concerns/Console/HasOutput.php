<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns\Console;

use Laravel\Prompts\Output\ConsoleOutput;
use PackageWizard\Installer\Support\Console;
use Symfony\Component\Console\Output\OutputInterface;

trait HasOutput
{
    protected static ?OutputInterface $output = null;

    protected static function output(int $verbosity = OutputInterface::VERBOSITY_NORMAL): OutputInterface
    {
        return static::$output ??= new ConsoleOutput($verbosity);
    }

    protected static function verboseWriteln(string $message): void
    {
        if (static::verbose()) {
            static::output()->writeln($message);
        }
    }

    protected static function verbose(): bool
    {
        return Console::verbose();
    }
}
