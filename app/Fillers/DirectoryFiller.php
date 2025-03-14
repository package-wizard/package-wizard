<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use Closure;

use function file_exists;
use function is_dir;
use function Laravel\Prompts\text;
use function preg_match;

/** @method static make(bool $local) */
class DirectoryFiller extends Filler
{
    public function __construct(
        protected readonly bool $local
    ) {}

    public function get(): string
    {
        if ($this->local) {
            return text(
                label      : 'What is the place of your project?',
                placeholder: 'E.g. ./example-app',
                required   : 'The project path is required.',
                validate   : $this->localValidator()
            );
        }

        return text(
            label      : 'What is the name of your project?',
            placeholder: 'E.g. example-app',
            required   : 'The project name is required.',
            validate   : $this->remoteValidator()
        );
    }

    protected function localValidator(): Closure
    {
        return static function (string $path): ?string {
            if (file_exists($path) && ! is_dir($path)) {
                return 'The object at the specified path is not a folder.';
            }

            return null;
        };
    }

    protected function remoteValidator(): Closure
    {
        return static function (string $value): ?string {
            if (preg_match('/[^\pL\pN\-_.]/', $value) !== 0) {
                return 'The name may only contain letters, numbers, dashes, underscores, and periods.';
            }

            return null;
        };
    }
}
