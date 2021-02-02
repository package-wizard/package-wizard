<?php

namespace Helldar\PackageWizard\Services;

use Composer\IO\IOInterface;
use Helldar\Support\Concerns\Makeable;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

final class Output
{
    use Makeable;

    /** @var \Composer\IO\IOInterface */
    protected $io;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    public function __construct(IOInterface $io, OutputInterface $output)
    {
        $this->io     = $io;
        $this->output = $output;
    }

    public function info(string $message): void
    {
        $this->prepareWriteLn(LogLevel::INFO, $message);
    }

    public function warning(string $message): void
    {
        $this->prepareWriteLn(LogLevel::WARNING, $message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param  mixed  $level
     * @param  string  $message
     */
    public function prepareWriteLn($level, string $message): void
    {
        $style     = $this->styles($level);
        $verbosity = $this->verbosity($level);

        $this->writeln($message, $style, $verbosity);
    }

    protected function writeln(string $message, string $style = null, int $verbosity = 0): void
    {
        $prefix = ! empty($style) ? "<{$style}>" : '';
        $suffix = ! empty($style) ? "</{$style}>" : '';

        $this->output->writeln($prefix . $message . $suffix, $verbosity);
    }

    protected function styles(string $level): ?string
    {
        $levels = [
            LogLevel::EMERGENCY => 'error',
            LogLevel::ALERT     => 'error',
            LogLevel::CRITICAL  => 'error',
            LogLevel::ERROR     => 'error',
            LogLevel::WARNING   => 'warning',
            LogLevel::NOTICE    => 'info',
            LogLevel::INFO      => 'info',
        ];

        return $levels[$level] ?? null;
    }

    protected function verbosity(string $level): int
    {
        $levels = [
            LogLevel::EMERGENCY => IOInterface::NORMAL,
            LogLevel::ALERT     => IOInterface::NORMAL,
            LogLevel::CRITICAL  => IOInterface::NORMAL,
            LogLevel::ERROR     => IOInterface::NORMAL,
            LogLevel::WARNING   => IOInterface::NORMAL,
            LogLevel::NOTICE    => IOInterface::VERBOSE,
            LogLevel::INFO      => IOInterface::VERY_VERBOSE,
        ];

        return $levels[$level] ?? IOInterface::NORMAL;
    }
}
