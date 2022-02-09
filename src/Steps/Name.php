<?php

namespace Helldar\PackageWizard\Steps;

use Helldar\Support\Facades\Helpers\Str;
use InvalidArgumentException;

final class Name extends BaseStep
{
    protected $question = 'Package name (<vendor>/<name>)';

    protected function input(): ?string
    {
        $package = $this->package();

        return $this->io->askAndValidate($this->question($package), static function ($value) use ($package) {
            if (Str::isEmpty($value)) {
                return $package;
            }

            if (! preg_match('{^[a-z0-9_.-]+/[a-z0-9_.-]+$}D', $value)) {
                throw new InvalidArgumentException('The package name ' . $value . ' is invalid, it should be lowercase and have a vendor name, a forward slash, and a package name, matching: [a-z0-9_.-]+/[a-z0-9_.-]+');
            }

            return $value;
        }, null, $package);
    }

    protected function package(): string
    {
        $name   = $this->name();
        $vendor = $this->vendor($name);

        return Str::lower($vendor) . '/' . Str::lower($name);
    }

    protected function name(): string
    {
        $name = basename(realpath('.'));

        return preg_replace('{(?:([a-z])([A-Z])|([A-Z])([A-Z][a-z]))}', '\\1\\3-\\2\\4', $name);
    }

    protected function vendor(string $name): string
    {
        switch (true) {
            case ! empty($_SERVER['COMPOSER_DEFAULT_VENDOR']):
                return $_SERVER['COMPOSER_DEFAULT_VENDOR'];

            case isset($this->git['github.user']):
                return $this->git['github.user'];

            case ! empty($_SERVER['USERNAME']):
                return $_SERVER['USERNAME'];

            case ! empty($_SERVER['USER']):
                return $_SERVER['USER'];

            case ! empty(get_current_user()):
                return get_current_user();

            default:
                return $name;
        }
    }

    protected function question(?string $question = null): string
    {
        $question = $this->question . ' [<comment>' . $question . '</comment>]';

        return parent::question($question);
    }
}
