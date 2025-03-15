<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use PackageWizard\Installer\Integrations\Packagist;

use function Laravel\Prompts\search;

/** @method static make() */
class PackageFiller extends Filler
{
    public function __construct(
        protected Packagist $packagist,
    ) {}

    public function get(): string
    {
        return search(
            label      : 'What is the name of project?',
            options    : fn (string $value) => $this->packagist->search($value),
            placeholder: 'E.g. monolog/monolog'
        );
    }
}
