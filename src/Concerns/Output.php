<?php

namespace Helldar\PackageWizard\Concerns;

use Composer\IO\IOInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Helper\FormatterHelper;
use Throwable;

trait Output
{
    public function info(string $message): void
    {
        $this->notice($message, LogLevel::INFO);
    }

    public function warning(string $message): void
    {
        $this->notice($message, LogLevel::WARNING);
    }

    public function error(string $message): void
    {
        $this->notice($message, LogLevel::ERROR);
    }

    public function throwError(Throwable $e, ...$values): void
    {
        $values = array_merge(['[' . get_class($e) . ']', $e->getMessage()], $values);

        $this->errorBlock($values, true);

        exit(1);
    }

    public function notice(string $message, string $level): void
    {
        $style     = $this->styles($level);
        $verbosity = $this->verbosity($level);

        $this->write($message, $style, $verbosity);
    }

    public function errorBlock($message, bool $large = false): void
    {
        $this->block($message, 'bg=red;fg=white', $large);
    }

    public function infoBlock($message, bool $large = false, ?string $substring = null): void
    {
        $message = (array) $message;

        if (! empty($substring)) {
            array_push($message, $substring);
        }

        $this->block($message, 'bg=blue;fg=white', $large);
    }

    public function lineBlock($message, bool $large = false): void
    {
        $message = $large ? ['', $message, ''] : $message;

        $this->write($message);
    }

    public function block($message, string $style, bool $large = false): void
    {
        $message = (array) $message;

        array_unshift($message, '');
        array_push($message, '');

        $message = $this->formatter()->formatBlock($message, $style, $large);

        $this->write($message);
    }

    public function write($message, ?string $style = null, int $verbosity = IOInterface::NORMAL): void
    {
        $prefix = ! empty($style) ? "<{$style}>" : '';
        $suffix = ! empty($style) ? "</{$style}>" : '';

        $message = is_string($message) ? $prefix . $message . $suffix : $message;

        $this->getIO()->write($message, true, $verbosity);
    }

    /**
     * @return \Symfony\Component\Console\Helper\FormatterHelper|\Symfony\Component\Console\Helper\Helper
     */
    protected function formatter(): FormatterHelper
    {
        return $this->getHelper('formatter');
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
