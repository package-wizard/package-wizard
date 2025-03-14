<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

use function implode;

readonly class ComposerService
{
    public function __construct(
        protected string $path
    ) {}

    public function find(): string
    {
        return implode(' ', $this->composer()->findComposer());
    }

    protected function composer(): Composer
    {
        return new Composer(new Filesystem(), $this->path);
    }
}
