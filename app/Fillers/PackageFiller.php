<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Fillers;

use PackageWizard\Installer\Integrations\Packagist;
use PackageWizard\Installer\Services\ProjectService;

use function Laravel\Prompts\search;
use function Laravel\Prompts\select;

/** @method static make() */
class PackageFiller extends Filler
{
    public function __construct(
        protected Packagist $packagist,
        protected ProjectService $project,
    ) {}

    public function get(): string
    {
        if (($name = $this->local()) !== ProjectService::Search) {
            return $name;
        }

        return $this->packagist();
    }

    protected function local(): ?string
    {
        return select(
            label   : 'Select a project:',
            options : $this->project->list(),
            scroll  : 20,
            required: 'The project is required.',
        );
    }

    protected function packagist(): string
    {
        return search(
            label      : 'What is the name of project?',
            options    : fn (string $value) => $this->packagist->search($value),
            placeholder: 'E.g. monolog/monolog',
            required   : 'The project name is required.',
        );
    }
}
