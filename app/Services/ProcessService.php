<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Laravel\Prompts\Output\ConsoleOutput;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function file_exists;
use function implode;
use function is_array;
use function is_readable;
use function Laravel\Prompts\warning;

class ProcessService
{
    protected static ?OutputInterface $output = null;

    protected static function output(): OutputInterface
    {
        return static::$output ??= new ConsoleOutput();
    }

    public function run(array|string $command, string $path): Process
    {
        $process = Process::fromShellCommandline($this->prepareCommand($command), $path);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            }
            catch (RuntimeException $e) {
                warning($e->getMessage());
            }
        }

        $process->run(fn ($type, $line) => static::output()->write('    ' . $line));

        return $process;
    }

    protected function prepareCommand(array|string $command): string
    {
        if (is_array($command)) {
            return implode(' && ', $command);
        }

        return $command;
    }
}
