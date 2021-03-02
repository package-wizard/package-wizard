<?php

namespace Helldar\PackageWizard\Services;

use Composer\IO\IOInterface;
use Helldar\Support\Facades\Helpers\Is;
use Helldar\Support\Facades\Helpers\Str;
use Helldar\Verbose\Facades\Log;
use Helldar\Verbose\Services\Logger as Service;

final class Logger
{
    public function set(IOInterface $io): void
    {
        Service::io($io);
    }

    public function write(array $messages): void
    {
        $lines = $this->clean($messages);

        $message = $this->compile($lines);

        Log::write($message);
    }

    protected function clean(array $values): array
    {
        return array_map(function ($value) {
            return $this->cast($value);
        }, $values);
    }

    protected function compile(array $values): string
    {
        $message = '';

        foreach ($values as $value) {
            $splitter = $this->splitter($value);

            $message .= $value . $splitter;
        }

        return $this->finish($message);
    }

    protected function cast($value): string
    {
        switch (true) {
            case Is::object($value):
                return get_class($value);

            default:
                return trim((string) $value);
        }
    }

    protected function splitter(string $value): string
    {
        return Str::startsWith($value, ['(', '"']) ? '' : ' ';
    }

    protected function finish(string $message): string
    {
        $message = trim($message);

        return Str::endsWith($message, [':', '?']) ? $message : $message . '.';
    }
}
