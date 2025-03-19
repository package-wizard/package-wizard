<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Concerns\Console;

use Illuminate\Support\Str;
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

    protected static function verboseWriteln(string $message, int $leftPad = 0): void
    {
        if (static::verbose()) {
            if ($leftPad) {
                $message = Str::padLeft(' ', $leftPad, ' ');
            }

            static::output()->writeln($message);
        }
    }

    protected static function verbose(): bool
    {
        return Console::verbose();
    }
}
