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
        if (($name = $this->local()) !== ProjectService::searchOn()) {
            return $name;
        }

        return $this->packagist();
    }

    protected function local(): ?string
    {
        return select(
            label   : __('form.field.template'),
            options : $this->project->list(),
            scroll  : 20,
            required: __('validation.required', ['attribute' => __('validation.attributes.template')]),
        );
    }

    protected function packagist(): string
    {
        return search(
            label      : __('form.field.template'),
            options    : fn (string $value) => $this->packagist->search($value),
            placeholder: __('form.hint.eg', ['example' => 'monolog/monolog']),
            required   : __('validation.required', ['attribute' => __('validation.attributes.template')]),
        );
    }
}
