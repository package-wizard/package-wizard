<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use PackageWizard\Installer\Integrations\Packagist;

use function Laravel\Prompts\search;
use function Laravel\Prompts\text;

/** @method static make(bool $local) */
class ProjectNameFiller extends Filler
{
    public function __construct(
        protected bool $local,
        protected Packagist $packagist,
    ) {}

    public function get(): string
    {
        return $this->local
            ? $this->path()
            : $this->packagist();
    }

    protected function path(): string
    {
        return text(
            label      : 'Specify the path to the project folder:',
            placeholder: 'E.g. ' . realpath('.'),
            required   : true,
            validate   : fn (string $path) => match (true) {
                ! realpath($path) => 'The specified path does not exist.',
                ! is_dir($path)   => 'The object at the specified path is not a folder.',
                default           => null
            },
            transform  : fn (string $value) => trim($value)
        );
    }

    protected function packagist(): string
    {
        return search(
            label      : 'What is the name of project?',
            options    : fn (string $value) => $this->packagist->search($value)
                ->pluck('name')
                ->all(),
            placeholder: 'E.g. monolog/monolog'
        );
    }
}
