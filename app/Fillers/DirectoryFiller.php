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
                label      : __('form.field.located'),
                placeholder: __('form.hint.eg', ['example' => './example-app']),
                required   : __('validation.required', ['attribute' => __('validation.attributes.project_path')]),
                validate   : $this->localValidator()
            );
        }

        return text(
            label      : __('form.field.place'),
            placeholder: __('form.hint.eg', ['example' => './example-app']),
            required   : __('validation.required', ['attribute' => __('validation.attributes.project_path')]),
            validate   : $this->remoteValidator()
        );
    }

    protected function localValidator(): Closure
    {
        return static function (string $path): ?string {
            if (file_exists($path) && ! is_dir($path)) {
                return __('validation.doesnt_folder');
            }

            return null;
        };
    }

    protected function remoteValidator(): Closure
    {
        return static function (string $value): ?string {
            if (preg_match('/[^\pL\pN\-_.]/', $value) !== 0) {
                return __('validation.alpha_dash');
            }

            return null;
        };
    }
}
